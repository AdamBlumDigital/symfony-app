<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\EventSubscriber;
use App\Modules\Module\Application\Model\CreateModuleCommand;
use App\Modules\Module\Application\Event\OnCreationRequestedEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class CreationRequestedEventSubscriber implements EventSubscriberInterface
{
	private MessageBusInterface $messageBus;

	public function __construct(
		MessageBusInterface $messageBus
	)
	{
		$this->messageBus = $messageBus;
	}

	public static function getSubscribedEvents(): array
    {
        return [
            OnCreationRequestedEvent::class => 'createModule',
        ];
    }

	public function createModule(OnCreationRequestedEvent $event): void
	{
		$createModuleCommand = new CreateModuleCommand();
		$createModuleCommand->setTitle($event->getTitle());	

		$this->messageBus->dispatch($createModuleCommand);
	}
}
