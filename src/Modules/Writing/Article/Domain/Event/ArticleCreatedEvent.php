<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Domain\Event;

use App\Modules\Writing\Article\Domain\Entity\ArticleId;
use App\Shared\Event\DomainEventInterface;
use Symfony\Contracts\EventDispatcher\Event;
use DateTimeImmutable;

final class ArticleCreatedEvent extends Event implements DomainEventInterface
{
	protected DateTimeImmutable $occur;

	protected ArticleId $articleId;

	public function __construct(ArticleId $articleId)
	{
		$this->articleId = $articleId;

		$this->occur = new DateTimeImmutable();
	}

	public function getArticleId(): ArticleId
	{
		return $this->articleId;
	}

	public function getOccur(): DateTimeImmutable
	{
		return $this->occur;
	}
}
