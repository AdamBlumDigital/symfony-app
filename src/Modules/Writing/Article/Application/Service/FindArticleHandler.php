<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Service;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use App\Modules\Writing\Article\Application\Model\FindArticleQuery;
use App\Modules\Writing\Article\Domain\Repository\ArticleRepositoryInterface;

#[AsMessageHandler]
final class FindArticleHandler
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

		$article = $this->articleRepository->findIfVisible($articleId);	

		return $article;
	}


}
