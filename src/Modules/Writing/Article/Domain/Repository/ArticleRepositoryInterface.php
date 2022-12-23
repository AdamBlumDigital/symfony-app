<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Domain\Repository;

use App\Modules\Writing\Article\Domain\Entity\Article;

interface ArticleRepositoryInterface
{
    /**
     *	@param	string $id
     *	@param	mixed $lockMode
     *	@param	mixed $lockVersion
     *
     *	@return ?array<Article>
     *
     *	@todo	Update parameters with proper types
     *			once they are determined.
     */
    public function find($id, $lockMode = null, $lockVersion = null);

    /**
     *	@param	array<mixed> $criteria
     *	@param	array<mixed> $orderBy = null
     *
     * @return ?Article
     */
    public function findOneBy(array $criteria, array $orderBy = null);

    /**
     * @return ?Article
     */
    public function findIfVisible(string $id);

    /**
     *	@return Article[]
     */
    public function findAll();

    /**
     *	@return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function findSome(int $page = 1, int $length = 1);

    /**
     *	@return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function findSomeByCategoryId(string $categoryId, int $page = 1, int $length = 1);

    public function save(Article $article): void;
}
