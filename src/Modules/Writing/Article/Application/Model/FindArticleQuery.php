<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Model;

/**
 *	This message is handled via sync transport
 */
final class FindArticleQuery
{
	private string $moduleId;

	public function __construct(string $moduleId)
	{
		$this->moduleId = $moduleId;
	}

	public function getArticleId(): string
	{
		return $this->moduleId;
	}
}
