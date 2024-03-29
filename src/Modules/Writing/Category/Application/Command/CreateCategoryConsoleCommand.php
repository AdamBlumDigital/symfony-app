<?php

declare(strict_types=1);

namespace App\Modules\Writing\Category\Application\Command;

use App\Modules\Writing\Category\Application\Model\CreateCategoryCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 *	@todo	Figure out how to use translations in Console Commands.
 *			using `getenv('LANG')` gets an immediate result (en_US.UTF-8)
 *			but there is likely a better way to do this.
 */
#[AsCommand(
    name: 'app:category:create',
    description: 'Creates a new Category'
)]
final class CreateCategoryConsoleCommand extends Command
{
    private MessageBusInterface $messageBus;

    public function __construct(
        MessageBusInterface $messageBus
    ) {
        $this->messageBus = $messageBus;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp('This command creates a new Category.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Category Creator');

        $title = $io->ask('Enter the Category title', null, function ($value) {
            if (!is_string($value)) {
                throw new \RuntimeException('The Category Title must be a string');
            }

            return $value;
        });

        $slugger = new AsciiSlugger();

        $slug = $slugger->slug(strval($title));

        $description = $io->ask('Enter the Category description', null, function ($value) {
            if (!is_string($value)) {
                throw new \RuntimeException('The Category Description must be a string');
            }

            return $value;
        });

        $asciiSlug = $slug->__toString();

        $this->messageBus->dispatch(new CreateCategoryCommand(strval($title), $asciiSlug, strval($description)));

        return Command::SUCCESS;
    }
}
