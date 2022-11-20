<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\EventSubscriber;
use App\Modules\Module\Application\Model\CreateModuleCommand;
use App\Modules\Module\Application\Event\OnCreationRequestedEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Psr\Log\LoggerInterface;
use App\Modules\Module\Domain\Event\ModuleCreatedEvent;

final class CreationRequestedEventSubscriber implements EventSubscriberInterface
{
	private MessageBusInterface $messageBus;	
	private LoggerInterface $logger;

	public function __construct(
		MessageBusInterface $messageBus,
		LoggerInterface $logger
	)
	{
		$this->messageBus = $messageBus;
		$this->logger = $logger;
	}

	/**
	 * Listen to OnCreationRequestedEvent
	 *
	 * @see	App\Modules\Module\Application\Event\OnCreationRequestedEvent
	 *
	 * The `ModuleCreatedEvent` is only here to serve as an example for now,
	 * since nothing utilizes this event yet.
	 */
	public static function getSubscribedEvents(): array
    {
        return [
            OnCreationRequestedEvent::class => 'createModule',
			ModuleCreatedEvent::class => 'testing'
        ];
    }

	/**
	 * When OnCreationRequestedEvent is received, 
	 * dispatch CreateModuleCommand
	 *
	 * @see App\Modules\Module\Application\Model\CreateModuleCommand
	 */
	public function createModule(OnCreationRequestedEvent $event): void
	{
		$this->logger->info('<OnCreationRequestedEvent> heard by <CreationRequestedEventSubscriber>');

		$this->logger->info('<CreateModuleCommand> will be instantiated');

		$createModuleCommand = new CreateModuleCommand();

		$createModuleCommand->setTitle($event->getTitle());	

		$this->logger->info('<CreateModuleCommand> will be dispatched');

		$this->messageBus->dispatch($createModuleCommand);
	}

	/**
	 *	Just an example of another event being subscribed to.
	 *
	 *	There will likely be a `ModuleCreatedEventSubscriber`
	 *	class soon enough that will listen to this event.
	 */
	public function testing(ModuleCreatedEvent $event): void
	{
		$serialized = \json_encode($event->getOccur());
		$this->logger->info('<Module> created: ' . $serialized);
	}
}
