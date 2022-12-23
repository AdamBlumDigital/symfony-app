<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Command;

use App\Modules\Writing\Article\Application\Event\OnArticleUpdateRequestedEvent;
use App\Modules\Writing\Article\Domain\Entity\Article;
use App\Modules\Writing\Article\Domain\Repository\ArticleRepositoryInterface;
use App\Modules\Writing\Category\Domain\Repository\CategoryRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

/**
 *	@todo	Figure out how to use translations in Console Commands.
 *			using `getenv('LANG')` gets an immediate result (en_US.UTF-8)
 *			but there is likely a better way to do this.
 */
#[AsCommand(
    name: 'app:article:update',
    description: 'Updates an Article'
)]
final class UpdateArticleConsoleCommand extends Command
{
    private ArticleRepositoryInterface $articleRepository;
    private CategoryRepositoryInterface $categoryRepository;
    private EventDispatcherInterface $eventDispatcher;

    private Article $article;
    private string $articleId;
    private string $articleTitle;
    private string $articleDescription;
    private string $articleCategoryId;
    private string $articleContent;
    private bool $articleIsVisible;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        ArticleRepositoryInterface $articleRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp('This command updates an Article.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /*
         *	Confirm:	List all Articles
         */
        if (!$io->confirm('List all Articles?', false)) {
            $io->error('Article Listing aborted.');

            return Command::FAILURE;
        }

        try {
            $this->setArticle($io);
            $this->articleTitle = $this->updateArticleTitle($io);
            $this->articleDescription = $this->updateArticleDescription($io);
            if ($io->confirm('Update Article Content?', false)) {
                $this->articleContent = $this->updateArticleContent($io);
            }
            $this->articleCategoryId = $this->updateArticleCategoryId($io);
            $this->articleIsVisible = $this->updateArticleIsVisible($io);
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());

            return Command::FAILURE;
        }

        $articleCategoryObject = $this->categoryRepository->findOneBy(['id' => $this->articleCategoryId]);
        if (null === $articleCategoryObject) {
            throw new \InvalidArgumentException(sprintf('No Category found with ID <%s>', $this->articleCategoryId));
        }
        $categoryTitle = $articleCategoryObject->getTitle();

        $io->definitionList(
            'Article Content',
            new TableSeparator(),
            ['ID' => $this->articleId],
            ['Title' => $this->articleTitle],
            ['Description' => $this->articleDescription],
            ['Category' => $categoryTitle],
        );

        /*
         *	Specifies mixed return type, though it always (in this
         *	use case) returns string.
         *	@see Symfony\Component\Console\Style\SymfonyStyle:245
         */
        /* Need to use "phpstan-ignore-next-line" if on level 9 */
        if ($io->confirm(sprintf('Update Article with title %s?', $this->articleTitle), false)) {
            $io->success('Dispatching Creation Request');

            /* Need to use "phpstan-ignore-next-line" if on level 9 */
            $this->eventDispatcher->dispatch(new OnArticleUpdateRequestedEvent(
                $this->articleId,
                $this->articleTitle,
                $this->articleDescription,
                $this->articleContent,
                $this->articleCategoryId,
                $this->articleIsVisible
            ));

            return Command::SUCCESS;
        } else {
            $io->error('Article Update aborted.');

            return Command::FAILURE;
        }
    }

    private function setArticle(SymfonyStyle $io): void
    {
        /**
         *	Set $articleArray values from the ArticleRepository.
         */
        $articles = $this->articleRepository->findAll();
        $articleArray = [];
        foreach ($articles as $article) {
            $artId = (string) $article->getId();
            $artTitle = $article->getTitle();

            $articleArray[$artId] = $artTitle;
        }

        /**
         *	Choose an Article (by UUID or Title).
         */
        $articleId = $io->choice('Choose an Article', $articleArray);

        /**
         *	Set the Selected Article.
         */
        $article = $this->articleRepository->findOneBy(['id' => $articleId]);

        if (null === $article) {
            throw new \Exception(sprintf('No article found with id <%s>', $articleId));
        }

        $articleCategory = $article->getCategory();
        if (null === $articleCategory) {
            throw new \Exception('No Category found');
        }

        $this->article = $article;
        $this->articleId = (string) $this->article->getId();
        $this->articleTitle = $this->article->getTitle();
        $this->articleDescription = $this->article->getDescription();
        $this->articleContent = $this->article->getContent() ?? ''; // nullable in Doctrine
        $this->articleCategoryId = (string) $articleCategory->getId();
        $this->articleIsVisible = $this->article->getIsVisible();
    }

    private function updateArticleTitle(SymfonyStyle $io): string
    {
        /**
         *	Ask: Article Title (defaults to original).
         */
        $updatedArticleTitle = $io->ask('Update the Article Title', $this->articleTitle);

        return $updatedArticleTitle;
    }

    private function updateArticleDescription(SymfonyStyle $io): string
    {
        /**
         *	Ask: Article Description (defaults to original).
         */
        $updatedArticleDescription = $io->ask('Update the Article Description', $this->articleDescription);

        return $updatedArticleDescription;
    }

    private function updateArticleContent(SymfonyStyle $io): string
    {
        /**
         * Create temporary file in which to
         * write content.
         */
        $filesystem = new Filesystem();
        $tempFile = $filesystem->tempnam('/tmp', 'editor_');
        /*
         *	Write current Article Content to
         *	temp file
         */
        $filesystem->dumpFile($tempFile, $this->articleContent);

        /**
         *	Select a text editor to edit content.
         */
        /** defaults to $EDITOR if it exists */
        $editorEnv = getenv('EDITOR'); // getenv returns string|false
        $defaultEditor = is_string($editorEnv) ? $editorEnv : null; // pass string|null to ask()
        $editor = $io->ask('Select text editor', $defaultEditor);

        /** ensure that the selected option is in fact an executable */
        $executableFinder = new ExecutableFinder();
        $executablePath = $executableFinder->find($editor);

        if (null === $executablePath) {
            throw new \InvalidArgumentException(sprintf('Process <%s> not found, aborting', $editor));
        }

        /** start the editor process */
        $editorProcess = new Process([$editor, $tempFile]);
        $editorProcess->setTty(true);
        $editorProcess->setTimeout(3600); // one hour
        $editorProcess->mustRun();

        $updatedArticleContent = file_get_contents($tempFile);

        if (false === $updatedArticleContent) {
            throw new FileNotFoundException(sprintf('File <%s> not found', $tempFile));
        }

        /* Delete the temp file */
        $filesystem->remove([$tempFile]);

        return $updatedArticleContent;
    }

    private function updateArticleCategoryId(SymfonyStyle $io): string
    {
        $categories = $this->categoryRepository->findAll();
        $categoryArray = [];
        foreach ($categories as $category) {
            $catId = (string) $category->getId();
            $catTitle = $category->getTitle();
            $categoryArray[$catId] = $catTitle;
        }

        /**
         *	There is no need for the Validator callback because the
         *	choice() method already handles this.
         */
        $updatedCategoryId = $io->choice('Choose a Category:', $categoryArray, $categoryArray[$this->articleCategoryId]);

        return $updatedCategoryId;
    }

    private function updateArticleIsVisible(SymfonyStyle $io): bool
    {
        /**
         *	Ask: Article Is Visible (defaults to original).
         */
        $updatedArticleIsVisible = $io->confirm('Should this Article be publicly visible?', $this->articleIsVisible);

        return $updatedArticleIsVisible;
    }
}
