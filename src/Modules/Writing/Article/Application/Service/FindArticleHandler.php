<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Service;

use App\Modules\Writing\Article\Application\Model\FindArticleQuery;
use App\Modules\Writing\Article\Domain\Repository\ArticleRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Psr\Log\LoggerInterface;

final class FindArticleHandler implements MessageHandlerInterface
{
	private ArticleRepositoryInterface $articleRepository;

	private LoggerInterface $logger;

	public function __construct(
		ArticleRepositoryInterface $articleRepository, 
		LoggerInterface $logger
	)
	{
		$this->articleRepository = $articleRepository;
		$this->logger = $logger;
	}

	public function __invoke(FindArticleQuery $findArticleQuery): mixed
	{
		$this->logger->info('<FindArticleHandler> Invoked');

		$articleId = $findArticleQuery->getArticleId();

		$this->logger->info(
			sprintf('<FindArticleHandler> looking for <Article> with ID <%s>', $articleId)
		);

		$article = $this->articleRepository->find($articleId);	

		return $article;
	}


}
