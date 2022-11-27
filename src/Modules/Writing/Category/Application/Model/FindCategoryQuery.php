<?php

declare(strict_types=1);

namespace App\Modules\Writing\Category\Application\Model;

/**
 *	This message is handled via sync transport
 */
final class FindCategoryQuery
{
	private string $categoryId;

	public function __construct(string $categoryId)
	{
		$this->categoryId = $categoryId;
	}

	public function getCategoryId(): string
	{
		return $this->categoryId;
	}
}
