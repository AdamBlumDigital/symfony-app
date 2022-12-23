<?php

declare(strict_types=1);

namespace App\Modules\Writing\Category\Domain\Repository;

use App\Modules\Writing\Category\Domain\Entity\Category;

interface CategoryRepositoryInterface
{
    /**
     *	@param	string $id
     *	@param	mixed $lockMode
     *	@param	mixed $lockVersion
     *
     *	@return ?array<Category>
     *
     *	@todo	Update parameters with proper types
     *			once they are determined.
     */
    public function find($id, $lockMode = null, $lockVersion = null);

    /**
     *	@param	array<mixed> $criteria
     *	@param	array<mixed> $orderBy = null
     *
     * @return ?Category
     */
    public function findOneBy(array $criteria, array $orderBy = null);

    /**
     *	@return Category[]
     */
    public function findAll();

    public function save(Category $category): void;
}
