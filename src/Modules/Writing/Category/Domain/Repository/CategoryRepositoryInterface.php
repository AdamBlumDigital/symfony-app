<?php

declare(strict_types=1);

namespace App\Modules\Writing\Category\Domain\Repository;

use App\Modules\Writing\Category\Domain\Entity\Category;

interface CategoryRepositoryInterface
{
	/**
	 *	@return ?array<Category>
	 *
	 *	@param	string $id
	 *	@param	mixed $lockMode
	 *	@param	mixed $lockVersion
	 *
	 *	@todo	Update parameters with proper types
	 *			once they are determined.
	 */
	public function find($id, $lockMode = null, $lockVersion = null);

	/**
	 *	@return Category[]
	 */
	public function findAll();

	public function save(Category $category): void;
}
