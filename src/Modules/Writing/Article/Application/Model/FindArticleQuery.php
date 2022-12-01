<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Model;

/**
 *	This message is handled via sync transport
 */
final class FindArticleQuery
{
	private string $articleId;

	public function __construct(string $articleId)
	{
		$this->articleId = $articleId;
	}

	public function getArticleId(): string
	{
		return $this->articleId;
	}
}
