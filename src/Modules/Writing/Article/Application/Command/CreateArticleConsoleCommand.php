<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Psr\EventDispatcher\EventDispatcherInterface;
use App\Modules\Writing\Article\Application\Event\OnArticleCreationRequestedEvent;
use App\Modules\Writing\Category\Domain\Repository\CategoryRepositoryInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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

		$title = $io->ask('Enter the Article title', null, function($value) {
			if (!is_string($value)) {
				throw new \RuntimeException('The Article Title must be a string');
			}

			return $value;
		});

		$description = $io->ask('Enter the Article description', null, function($value) {
			if (!is_string($value)) {
				throw new \RuntimeException('The Article Description must be a string');
			}

			return $value;
		});

		$categories = $this->categoryRepository->findAll();
		$categoryArray = array();
		foreach ($categories as $category) {
			$catId = (string) $category->getId();
			$catTitle = $category->getTitle();
			$categoryArray[$catId] = $catTitle;
		}

		$categoryId = $io->choice('Choose a Category:', $categoryArray);

		/**
		 * Create temporary file in which to
		 * write content
		 */
		$filesystem = new Filesystem();
		$tempFile = $filesystem->tempnam('/tmp', 'editor_', '.md');

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

		$content = file_get_contents($tempFile);
		
		$filesystem->remove([$tempFile]);

		/**
		 *	Specifies mixed return type, though it always (in this 
		 *	use case) returns string.
		 *	@see Symfony\Component\Console\Style\SymfonyStyle:245	
		 */
		/* Need to use "phpstan-ignore-next-line" if on level 9 */
        if ($io->confirm(sprintf('Create Article with title %s?', $title), false)) {

			$io->success('Dispatching Creation Request');
			/** @phpstan-ignore-next-line */
			$this->eventDispatcher->dispatch(new OnArticleCreationRequestedEvent(
				$title, 
				$description, 
				$content, 
				$categoryId
			));
			return Command::SUCCESS;
		} else {
            $io->error('Article Creation aborted.');
    		return Command::FAILURE;
		}
	}
}
