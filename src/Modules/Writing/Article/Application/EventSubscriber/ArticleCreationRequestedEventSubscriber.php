<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\EventSubscriber;
use App\Modules\Writing\Article\Application\Model\CreateArticleCommand;
use App\Modules\Writing\Article\Application\Event\OnArticleCreationRequestedEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Psr\Log\LoggerInterface;
use App\Modules\Writing\Article\Domain\Event\ArticleCreatedEvent;

final class ArticleCreationRequestedEventSubscriber implements EventSubscriberInterface
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
	 * @see	App\Modules\Writing\Article\Application\Event\OnArticleCreationRequestedEvent
	 *
	 * The `ArticleCreatedEvent` is only here to serve as an example for now,
	 * since nothing utilizes this event yet.
	 */
	public static function getSubscribedEvents(): array
    {
        return [
            OnArticleCreationRequestedEvent::class => 'createArticle',
			ArticleCreatedEvent::class => 'testing'
        ];
    }

	/**
	 * When OnCreationRequestedEvent is received, 
	 * dispatch CreateArticleCommand
	 *
	 * @see App\Modules\Article\Application\Model\CreateArticleCommand
	 */
	public function createArticle(OnArticleCreationRequestedEvent $event): void
	{
		$this->logger->info('<OnCreationRequestedEvent> heard by <CreationRequestedEventSubscriber>');

		$this->logger->info('<CreateArticleCommand> will be instantiated');

		$createArticleCommand = new CreateArticleCommand();

		$createArticleCommand->setTitle($event->getTitle());	
		$createArticleCommand->setDescription($event->getDescription());	
		$createArticleCommand->setContent($event->getContent());	

		$this->logger->info('<CreateArticleCommand> will be dispatched');

		$this->messageBus->dispatch($createArticleCommand);
	}

	/**
	 *	Just an example of another event being subscribed to.
	 *
	 *	There will likely be a `ArticleCreatedEventSubscriber`
	 *	class soon enough that will listen to this event.
	 */
	public function testing(ArticleCreatedEvent $event): void
	{
		$serialized = \json_encode($event->getOccur());
		$this->logger->info('<Article> created: ' . $serialized);
	}
}
