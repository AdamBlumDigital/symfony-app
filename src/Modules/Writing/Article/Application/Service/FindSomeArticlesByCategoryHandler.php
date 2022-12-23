<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Service;

use App\Modules\Writing\Article\Application\Model\FindSomeArticlesByCategoryQuery;
use App\Modules\Writing\Article\Domain\Repository\ArticleRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class FindSomeArticlesByCategoryHandler
{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function __invoke(FindSomeArticlesByCategoryQuery $findSomeArticlesByCategoryQuery): mixed
    {
        $categoryId = $findSomeArticlesByCategoryQuery->getCategoryId();
        $page = $findSomeArticlesByCategoryQuery->getPageNumber();
        $size = $findSomeArticlesByCategoryQuery->getPageSize();

        $articles = $this->articleRepository->findSomeByCategoryId($categoryId, $page, $size);

        return $articles;
    }
}
