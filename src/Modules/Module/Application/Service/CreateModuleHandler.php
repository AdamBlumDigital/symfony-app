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
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Ulid;

final class CreateModuleHandler implements MessageHandlerInterface
{
	private ModuleRepositoryInterface $moduleRepository;
	private EventDispatcherInterface $eventDispatcher;
	private RequestStack $requestStack;
	private SerializerInterface $serializer;

	public function __construct(
		ModuleRepositoryInterface $moduleRepository,
		EventDispatcherInterface $eventDispatcher,
		RequestStack $requestStack,
		SerializerInterface $serializer
	)
	{
		$this->moduleRepository = $moduleRepository;
		$this->eventDispatcher = $eventDispatcher;
		$this->requestStack = $requestStack;
		$this->serializer = $serializer;
	}

	public function __invoke(CreateModuleCommand $createModuleCommand):void
	{
		$module = Module::create(
			new ModuleId(Ulid::generate()),
			'Title'
		);	

		$this->moduleRepository->save($module);

		$this->requestStack->getSession()->set(
			'last_article_created',
			$this->serializer->serialize($module, 'json')
		);

		foreach ($module->pullDomainEvents() as $domainEvent) {
			$this->eventDispatcher->dispatch($domainEvent);
		}
	}

}	
