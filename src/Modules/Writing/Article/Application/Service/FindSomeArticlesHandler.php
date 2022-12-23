<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Service;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use App\Modules\Writing\Article\Application\Model\FindSomeArticlesQuery;
use App\Modules\Writing\Article\Domain\Repository\ArticleRepositoryInterface;

#[AsMessageHandler]
final class FindSomeArticlesHandler
{
	private ArticleRepositoryInterface $articleRepository;

	public function __construct(ArticleRepositoryInterface $articleRepository)
	{
		$this->articleRepository = $articleRepository;
	}

	public function __invoke(FindSomeArticlesQuery $findSomeArticlesQuery): mixed
	{
		$page = $findSomeArticlesQuery->getPageNumber();
		$size = $findSomeArticlesQuery->getPageSize();

		$articles = $this->articleRepository->findSome($page, $size);	

		return $articles;
	}
}
