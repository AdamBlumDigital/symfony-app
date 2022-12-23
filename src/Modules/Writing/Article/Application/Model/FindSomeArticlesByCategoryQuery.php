<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Model;

/**
 *	This message is handled via sync transport
 */
final class FindSomeArticlesByCategoryQuery
{
	private string $categoryId;
	private int $pageNumber;
	private int $pageSize;

	public function __construct(string $categoryId, int $pageNumber, int $pageSize)
	{
		$this->categoryId = $categoryId;
		$this->pageNumber = $pageNumber;
		$this->pageSize = $pageSize;
	}
	
	public function getCategoryId(): string
	{
		return $this->categoryId;
	}

	public function getPageNumber(): int
	{
		return $this->pageNumber;
	}

	public function getPageSize(): int
	{
		return $this->pageSize;
	}
}
