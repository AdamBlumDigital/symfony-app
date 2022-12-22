<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Service;

use App\Modules\Writing\Article\Application\Model\UpdateArticleCommand;
use App\Modules\Writing\Article\Domain\Entity\Article;
use App\Modules\Writing\Article\Domain\Entity\ArticleId;
use App\Modules\Writing\Article\Domain\Repository\ArticleRepositoryInterface;
use App\Modules\Writing\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Shared\ValueObject\AggregateRootId;
//use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Uid\Uuid;

final class UpdateArticleHandler implements MessageHandlerInterface
{
	private ArticleRepositoryInterface $articleRepository;
	private CategoryRepositoryInterface $categoryRepository;
	//private EventDispatcherInterface $eventDispatcher;

	public function __construct(
		ArticleRepositoryInterface $articleRepository,
		CategoryRepositoryInterface $categoryRepository,
		//EventDispatcherInterface $eventDispatcher,
	)
	{
		$this->articleRepository = $articleRepository;
		$this->categoryRepository = $categoryRepository;
		//$this->eventDispatcher = $eventDispatcher;
	}

	public function __invoke(UpdateArticleCommand $updateArticleCommand):void
	{
		$categoryId = $updateArticleCommand->getCategoryId();

		$categoryObject = $this->categoryRepository->findOneBy(['id' => $categoryId]);

		if ($categoryObject == null) {
			throw new UnrecoverableMessageHandlingException(sprintf('No Category with ID <%s>', $categoryId));
		}


		$articleId = $updateArticleCommand->getId();
		$article = $this->articleRepository->findOneBy(['id' => $articleId]);

		if ($article == null) {
			throw new UnrecoverableMessageHandlingException(sprintf('No Article with ID <%s>', $articleId));
		}

		$article->setTitle($updateArticleCommand->getTitle());
		$article->setDescription($updateArticleCommand->getDescription());
		$article->setContent($updateArticleCommand->getContent());
		$article->setCategory($categoryObject);

		$this->articleRepository->save($article);

		/**
		 *	Do not call pullDomainEvents() more than once
		 */
		/*
		foreach ($article->pullDomainEvents() as $domainEvent) {
			$this->eventDispatcher->dispatch($domainEvent);
		}*/
	}

}	
