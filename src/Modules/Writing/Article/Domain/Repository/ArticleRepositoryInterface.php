<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Domain\Repository;

use App\Modules\Writing\Article\Domain\Entity\Article;

interface ArticleRepositoryInterface
{
	/**
	 *	@return ?array<Article>
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
	 *	@return Article[]
	 */
	public function findAll();

	public function save(Article $article): void;
}
