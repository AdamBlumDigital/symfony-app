<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Psr\EventDispatcher\EventDispatcherInterface;
use App\Modules\Module\Application\Event\OnCreationRequestedEvent;

#[AsCommand(
	name:			'app:create-module',
	description:	'Creates a new Module'
)]
final class CreateModuleConsoleCommand extends Command
{
	private EventDispatcherInterface $eventDispatcher;	

	public function __construct(EventDispatcherInterface $eventDispatcher)
	{
		$this->eventDispatcher = $eventDispatcher;

		parent::__construct();
	}

	protected function configure(): void
	{
		$this->setHelp('This command creates a new Module with the specified title.');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$helper = $this->getHelper('question');

		$output->writeln([
			'Module Creator',
			'--------------'
		]);

		$question = new Question('Enter the Module Title: ');
		$title = $helper->ask($input, $output, $question);

		$output->writeln(['Creating Module']);


		$this->eventDispatcher->dispatch(new OnCreationRequestedEvent(
			$title
		));
		
		return Command::SUCCESS;
	}
}
