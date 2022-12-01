<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Service;

use App\Modules\Writing\Article\Application\Model\FindArticleQuery;
use App\Modules\Writing\Article\Domain\Repository\ArticleRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class FindArticleHandler implements MessageHandlerInterface
{
	private ArticleRepositoryInterface $articleRepository;

	public function __construct(
		ArticleRepositoryInterface $articleRepository
	)
	{
		$this->articleRepository = $articleRepository;
	}

	public function __invoke(FindArticleQuery $findArticleQuery): mixed
	{
		$articleId = $findArticleQuery->getArticleId();

		$article = $this->articleRepository->find($articleId);	

		return $article;
	}


}
