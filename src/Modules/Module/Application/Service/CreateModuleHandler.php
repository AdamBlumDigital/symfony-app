<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\Service;

use App\Modules\Module\Application\Model\CreateModuleCommand;
use App\Modules\Module\Domain\Entity\Module;
use App\Modules\Module\Domain\Entity\ModuleId;
use App\Modules\Module\Domain\Repository\ModuleRepositoryInterface;
use App\Shared\ValueObject\AggregateRootId;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Uid\Uuid;
use Psr\Log\LoggerInterface;

final class CreateModuleHandler implements MessageHandlerInterface
{
	private ModuleRepositoryInterface $moduleRepository;
	private EventDispatcherInterface $eventDispatcher;
	private LoggerInterface $logger;

	public function __construct(
		ModuleRepositoryInterface $moduleRepository,
		EventDispatcherInterface $eventDispatcher,
		LoggerInterface $logger
	)
	{
		$this->moduleRepository = $moduleRepository;
		$this->eventDispatcher = $eventDispatcher;
		$this->logger = $logger;
	}

	public function __invoke(CreateModuleCommand $createModuleCommand):void
	{
		$this->logger->info('<CreateModuleHandler> Invoked');
		$module = Module::create(
			new ModuleId(
				Uuid::v4()->jsonSerialize()	// Return UUID as string, not object
			),
			$createModuleCommand->getTitle()
		);

		$this->logger->info('New <Module> being saved to <ModuleRepository>');
		$this->moduleRepository->save($module);

		foreach ($module->pullDomainEvents() as $domainEvent) {
			$this->logger->info('Dispatching domain events');
			$this->eventDispatcher->dispatch($domainEvent);
		}
	}

}	
