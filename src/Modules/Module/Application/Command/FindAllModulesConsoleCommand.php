<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Modules\Module\Application\Event\OnCreationRequestedEvent;
use App\Modules\Module\Domain\Repository\ModuleRepositoryInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 *	@todo	Figure out how to use translations in Console Commands.
 *			using `getenv('LANG')` gets an immediate result (en_US.UTF-8)
 *			but there is likely a better way to do this.
 */

#[AsCommand(
	name:			'app:module:find:all',
	description:	'Finds all Modules'
)]
final class FindAllModulesConsoleCommand extends Command
{
	private ModuleRepositoryInterface $moduleRepository;
	private SerializerInterface $serializer;

	public function __construct(
		SerializerInterface $serializer,
		ModuleRepositoryInterface $moduleRepository
	)
	{
		$this->moduleRepository = $moduleRepository;
		$this->serializer = $serializer;
		parent::__construct();
	}

	protected function configure(): void
	{
		$this->setHelp('This command lists all Modules.');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
        $io = new SymfonyStyle($input, $output);
		
		if ($io->confirm('List all Modules? This could take a lot of memory.', false)) {

    	    $io->title('All Modules');
			
			$modules = $this->moduleRepository->findAll();	

			$normalizedModules = $this->serializer->normalize($modules);

			/**
			 *	@todo	Lord forgive me...
			 */
			foreach ($normalizedModules as $module) {
				$io->definitionList(
					$module['id']['value'],
					['title' => $module['title']]
				);
			}

			return Command::SUCCESS;

		} else {	
            $io->error('Module Listing aborted.');
    		return Command::FAILURE;
		}



	}
}
