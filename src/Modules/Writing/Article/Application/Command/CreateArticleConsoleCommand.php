<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Psr\EventDispatcher\EventDispatcherInterface;
use App\Modules\Writing\Article\Application\Event\OnArticleCreationRequestedEvent;
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

	public function __construct(EventDispatcherInterface $eventDispatcher)
	{
		$this->eventDispatcher = $eventDispatcher;

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

		/**
		 *	Specifies mixed return type, though it always (in this 
		 *	use case) returns string.
		 *	@see Symfony\Component\Console\Style\SymfonyStyle:245	
		 */
		/** @phpstan-ignore-next-line */
        if ($io->confirm(sprintf('Create Article with title %s?', $title), false)) {

			$io->success('Dispatching Creation Request');
			/** @phpstan-ignore-next-line */
			$this->eventDispatcher->dispatch(new OnArticleCreationRequestedEvent($title));
			return Command::SUCCESS;
		} else {
            $io->error('Article Creation aborted.');
    		return Command::FAILURE;
		}
	}
}
