<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\EventSubscriber;

use App\Modules\Writing\Article\Application\Event\OnArticleUpdateRequestedEvent;
use App\Modules\Writing\Article\Application\Model\UpdateArticleCommand;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class ArticleUpdateRequestedEventSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $messageBus;

    public function __construct(
        MessageBusInterface $messageBus
    ) {
        $this->messageBus = $messageBus;
    }

    /**
     * Listen to OnUpdateRequestedEvent.
     *
     * @see	App\Modules\Writing\Article\Application\Event\OnArticleUpdateRequestedEvent
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OnArticleUpdateRequestedEvent::class => 'updateArticle',
        ];
    }

    /**
     * When OnUpdateRequestedEvent is received,
     * dispatch UpdateArticleCommand.
     *
     * @see App\Modules\Article\Application\Model\UpdateArticleCommand
     */
    public function updateArticle(OnArticleUpdateRequestedEvent $event): void
    {
        $updateArticleCommand = new UpdateArticleCommand();

        $updateArticleCommand->setId($event->getId());
        $updateArticleCommand->setTitle($event->getTitle());
        $updateArticleCommand->setDescription($event->getDescription());
        $updateArticleCommand->setContent($event->getContent());
        $updateArticleCommand->setCategoryId($event->getCategoryId());
        $updateArticleCommand->setIsVisible($event->getIsVisible());

        $this->messageBus->dispatch($updateArticleCommand);
    }
}
