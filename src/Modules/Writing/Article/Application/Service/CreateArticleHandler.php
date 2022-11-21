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

final class CreateArticleHandler implements MessageHandlerInterface
{
	private ArticleRepositoryInterface $articleRepository;
	private EventDispatcherInterface $eventDispatcher;

	public function __construct(
		ArticleRepositoryInterface $articleRepository,
		EventDispatcherInterface $eventDispatcher,
	)
	{
		$this->articleRepository = $articleRepository;
		$this->eventDispatcher = $eventDispatcher;
	}

	public function __invoke(CreateArticleCommand $createArticleCommand):void
	{
		$article = Article::create(
			new ArticleId(
				Uuid::v4()->jsonSerialize()	// Return UUID as string, not object
			),
			$createArticleCommand->getTitle(),
			$createArticleCommand->getDescription()
		);

		$this->articleRepository->save($article);

		/**
		 *	Do not call pullDomainEvents() more than once
		 */
		foreach ($article->pullDomainEvents() as $domainEvent) {
			$this->eventDispatcher->dispatch($domainEvent);
		}
	}

}	
