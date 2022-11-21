<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Service;

use App\Modules\Writing\Article\Application\Model\CreateArticleCommand;
use App\Modules\Writing\Article\Domain\Entity\Article;
use App\Modules\Writing\Article\Domain\Entity\ArticleId;
use App\Modules\Writing\Article\Domain\Repository\ArticleRepositoryInterface;
use App\Shared\ValueObject\AggregateRootId;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Uid\Uuid;
use Psr\Log\LoggerInterface;

final class CreateArticleHandler implements MessageHandlerInterface
{
	private ArticleRepositoryInterface $articleRepository;
	private EventDispatcherInterface $eventDispatcher;
	private LoggerInterface $logger;

	public function __construct(
		ArticleRepositoryInterface $articleRepository,
		EventDispatcherInterface $eventDispatcher,
		LoggerInterface $logger
	)
	{
		$this->articleRepository = $articleRepository;
		$this->eventDispatcher = $eventDispatcher;
		$this->logger = $logger;
	}

	public function __invoke(CreateArticleCommand $createArticleCommand):void
	{
		$this->logger->info('<CreateArticleHandler> Invoked');

		$this->logger->info('<Article> will be created');

		$article = Article::create(
			new ArticleId(
				Uuid::v4()->jsonSerialize()	// Return UUID as string, not object
			),
			$createArticleCommand->getTitle()
		);

		$this->logger->info('<Article> being saved to <ArticleRepository>');

		$this->articleRepository->save($article);

		$this->logger->info('<Article> Domain Events will be dispatched');

		/**
		 *	Do not call pullDomainEvents() more than once
		 */
		foreach ($article->pullDomainEvents() as $domainEvent) {
			$this->logger->info('<Article> Domain Event dispatched');
			$this->eventDispatcher->dispatch($domainEvent);
		}
	}

}	
