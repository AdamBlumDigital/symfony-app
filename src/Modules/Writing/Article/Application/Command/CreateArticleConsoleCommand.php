<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Psr\EventDispatcher\EventDispatcherInterface;
use App\Modules\Writing\Article\Application\Event\OnArticleCreationRequestedEvent;
use App\Modules\Writing\Category\Domain\Repository\CategoryRepositoryInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Exception\ValidatorFailedException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *	@todo	Figure out how to use translations in Console Commands.
 *			using `getenv('LANG')` gets an immediate result (en_US.UTF-8)
 *			but there is likely a better way to do this.
 */

#[AsCommand(
	name:			'app:article:create',
	description:	'Creates a new Article'
)]
final class CreateArticleConsoleCommand extends Command
{
	private EventDispatcherInterface $eventDispatcher;
	private CategoryRepositoryInterface $categoryRepository;

	private string $articleTitle;
	private string $articleDescription;
	private string $articleCategoryId;
	private string $articleContent;
	private bool $articleIsVisible;

	public function __construct(
		EventDispatcherInterface $eventDispatcher,
		CategoryRepositoryInterface $categoryRepository
	)
	{
		$this->eventDispatcher = $eventDispatcher;
		$this->categoryRepository = $categoryRepository;

		parent::__construct();
	}

	protected function configure(): void
	{
		$this->setHelp('This command creates a new Article.');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
        $io = new SymfonyStyle($input, $output);

        $io->title('Article Creator');
		
		try {
			$this->articleTitle = $this->setArticleTitle($io);
			$this->articleDescription = $this->setArticleDescription($io);
			$this->articleCategoryId = $this->setArticleCategoryId($io);
			$this->articleContent = $this->setArticleContent($io);
			$this->articleIsVisible = $this->setArticleIsVisible($io);
		} catch (\Exception $exception) {
			$io->error($exception->getMessage());
			return Command::FAILURE;
		}

		$articleCategoryObject = $this->categoryRepository->findOneBy(['id' => $this->articleCategoryId]);
		if ($articleCategoryObject === null) {
			throw new \InvalidArgumentException(
				sprintf('No Category found with ID <%s>', $this->articleCategoryId)
			);
		}
		$articleCategoryTitle = $articleCategoryObject->getTitle();

		$io->definitionList(
			'Article Content',
			new TableSeparator(),
			['Title' => $this->articleTitle],
			['Description' => $this->articleDescription],
			['Category' => $articleCategoryTitle],
			['Content' => $this->articleContent],
			['Visible?' => ($this->articleIsVisible) ? 'true' : 'false'],
		);

		/**
		 *	Specifies mixed return type, though it always (in this 
		 *	use case) returns string.
		 *	@see Symfony\Component\Console\Style\SymfonyStyle:245	
		 */
		/* Need to use "phpstan-ignore-next-line" if on level 9 */
        if ($io->confirm('Create Article?', false)) {

			$io->success('Dispatching Creation Request');

			/* Need to use "phpstan-ignore-next-line" if on level 9 */
			$this->eventDispatcher->dispatch(new OnArticleCreationRequestedEvent(
				$this->articleTitle, 
				$this->articleDescription, 
				$this->articleContent,
				$this->articleCategoryId,
				$this->articleIsVisible
			));
			return Command::SUCCESS;
		} else {
            $io->error('Article Creation aborted.');
    		return Command::FAILURE;
		}
	}
	
	private function setArticleTitle(SymfonyStyle $io): string
	{
		$title = $io->ask('Enter the Article title', null, Validation::createCallable(
			new Assert\NotBlank(),
			new Assert\Length(['min' => 4, 'max' => 255])
		));
		return $title;
	}

	private function setArticleDescription(SymfonyStyle $io): string
	{
		$description = $io->ask('Enter the Article description', null, Validation::createCallable(
			new Assert\NotBlank(),
			new Assert\Length(['min' => 4, 'max' => 255])
		));

		return $description;
	}

	private function setArticleCategoryId(SymfonyStyle $io): string
	{
		$categories = $this->categoryRepository->findAll();
		$categoryArray = array();
		foreach ($categories as $category) {
			$catId = (string) $category->getId();
			$catTitle = $category->getTitle();
			$categoryArray[$catId] = $catTitle;
		}

		/**
		 *	There is no need for the Validator callback because the
		 *	choice() method already handles this.
		 */
		$categoryId = $io->choice('Choose a Category:', $categoryArray);

		return $categoryId;
	}

	private function setArticleContent(SymfonyStyle $io): string
	{
		/**
		 * Create temporary file in which to write content
		 */
		$filesystem = new Filesystem();
		$tempFile = $filesystem->tempnam('/tmp', 'editor_', '.md');

		/**
		 *	Select a text editor to edit content
		 */
		/** defaults to $EDITOR if it exists */
		$editorEnv = getenv('EDITOR'); // getenv returns string|false
		$defaultEditor = is_string($editorEnv) ? $editorEnv : null; // pass string|null to ask()
		$editor = $io->ask('Select text editor', $defaultEditor);

		/** ensure that the selected option is in fact an executable */
		$executableFinder = new ExecutableFinder();
		$executablePath = $executableFinder->find($editor);

		if ($executablePath === null) {
			throw new \InvalidArgumentException(sprintf('Process <%s> not found, aborting', $editor));
		}

		/** start the editor process */
		$editorProcess = new Process([$editor, $tempFile]);
		$editorProcess->setTty(true);
		$editorProcess->setTimeout(3600); // one hour
		$editorProcess->mustRun();

		$content = file_get_contents($tempFile);

		if ($content === false) {
			throw new FileNotFoundException('File not found');
		}
		
		$filesystem->remove([$tempFile]);

		return $content;
	}
	
	private function setArticleIsVisible(SymfonyStyle $io): bool
	{
		$isVisible = $io->confirm('Should this article be publicly visible?', false);

		return $isVisible;
	}
}
