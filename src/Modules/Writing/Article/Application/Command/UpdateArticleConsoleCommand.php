<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Modules\Writing\Article\Application\Event\OnArticleUpdateRequestedEvent;
use App\Modules\Writing\Article\Domain\Repository\ArticleRepositoryInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 *	@todo	Figure out how to use translations in Console Commands.
 *			using `getenv('LANG')` gets an immediate result (en_US.UTF-8)
 *			but there is likely a better way to do this.
 */

#[AsCommand(
	name:			'app:article:update',
	description:	'Updates an Articles'
)]
final class UpdateArticleConsoleCommand extends Command
{
	private const ANSI_CLEAR = "\033\143";
	private ArticleRepositoryInterface $articleRepository;
	private EventDispatcherInterface $eventDispatcher;

	public function __construct(
		EventDispatcherInterface $eventDispatcher,
		ArticleRepositoryInterface $articleRepository
	)
	{
		$this->eventDispatcher = $eventDispatcher;
		$this->articleRepository = $articleRepository;
		parent::__construct();
	}

	protected function configure(): void
	{
		$this->setHelp('This command updates an Articles.');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
        $io = new SymfonyStyle($input, $output);

		/**
		 *	Confirm:	List all Articles
		 */	
		if ($io->confirm('List all Articles? This could take a lot of memory.', false)) {
			$output->write(sprintf(self::ANSI_CLEAR));
			/** Heading */
    	    $io->title('All Articles');
			
			/**
			 *	Set $articleArray values from the ArticleRepository
			 */
			$articles = $this->articleRepository->findAll();
			$articleArray = array();
			foreach ($articles as $article) {
				$artId = (string) $article->getId();
				$artTitle = $article->getTitle();
				$articleArray[$artId] = $artTitle;
			}

			/**
			 *	Choose an Article (by UUID or Title)
			 */
			$articleId = $io->choice('Choose an Article:', $articleArray);
			$output->write(sprintf(self::ANSI_CLEAR));

			/**
			 *	Set the Selected Article
			 */
			$selectedArticle = $this->articleRepository->findOneBy(['id' => $articleId]);

			$originalArticleTitle = $selectedArticle->getTitle();
			$originalArticleDescription = $selectedArticle->getDescription();
			$originalArticleContent = $selectedArticle->getContent();

			/**
			 *	Ask: Article Title (defaults to original)
			 */
			$updatedArticleTitle = $io->ask('Update the Article Title:', $originalArticleTitle, function($value) {
				if (!is_string($value)) {
					throw new \RuntimeException('The Article Title must be a string');
				}
		
				return $value;
			});
			$output->write(sprintf(self::ANSI_CLEAR));

			/**
			 *	Ask: Article Description (defaults to original)
			 */
			$updatedArticleDescription = $io->ask('Update the Article Description:', $originalArticleDescription, function($value) {
				if (!is_string($value)) {
					throw new \RuntimeException('The Article Description must be a string');
				}
		
				return $value;
			});
			$output->write(sprintf(self::ANSI_CLEAR));
	
			/**
			 * Create temporary file in which to
			 * write content
			 */
			$filesystem = new Filesystem();
			$tempFile = $filesystem->tempnam('/tmp', 'editor_');

			$filesystem->dumpFile($tempFile, $originalArticleContent);
	
			/**
			 *	Open a text editor to edit content
			 */
			$editorProcess = new Process(['vim', $tempFile]);
			$editorProcess->setTty(true);
			$editorProcess->setTimeout(3600); // one hour	
			try {
				$editorProcess->mustRun();
			} catch (ProcessFailedException $exception) {
				$io->error($exception->getMessage());
			}
	
			$updatedArticleContent = $originalArticleContent; 
	
			/**
			 *	Attempt to read the temp file that
			 *	contains the updated Article Content
			 */
			try {
				$updatedArticleContent = file_get_contents($tempFile);
			} catch (FileNotFoundException $exception) {
				$io->error($exception->getMessage());
			}
			
			/**
			 *	Delete the temp file
			 */
			$filesystem->remove([$tempFile]);
			$output->write(sprintf(self::ANSI_CLEAR));

			/**
			 *	@todo	Add Category update feature
			 */
			$updatedCategoryId = (string) $selectedArticle->getCategory()->getId();

			/**
			 *	Specifies mixed return type, though it always (in this 
			 *	use case) returns string.
			 *	@see Symfony\Component\Console\Style\SymfonyStyle:245	
			 */
			/* Need to use "phpstan-ignore-next-line" if on level 9 */
    	    if ($io->confirm(sprintf('Update Article with title %s?', $updatedArticleTitle), false)) {

				$io->success('Dispatching Creation Request');

				/* Need to use "phpstan-ignore-next-line" if on level 9 */
				$this->eventDispatcher->dispatch(new OnArticleUpdateRequestedEvent(
					$articleId,
					$updatedArticleTitle, 
					$updatedArticleDescription, 
					(string) $updatedArticleContent, 
					$updatedCategoryId
				));

				return Command::SUCCESS;
			} else {

    	        $io->error('Article Update aborted.');

    			return Command::FAILURE;
			}

		} else {	
			/**
			 *	Do not return a list of Articles
			 */
            $io->error('Article Listing aborted.');
    		return Command::FAILURE;
		}
	}
}
