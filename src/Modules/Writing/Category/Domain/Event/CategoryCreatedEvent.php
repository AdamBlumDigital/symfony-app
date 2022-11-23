<?php

declare(strict_types=1);

namespace App\Modules\Writing\Category\Domain\Event;

use App\Modules\Writing\Shared\Domain\Entity\ValueObject\CategoryId;
use App\Shared\Event\DomainEventInterface;
use Symfony\Contracts\EventDispatcher\Event;
use DateTimeImmutable;

final class CategoryCreatedEvent extends Event implements DomainEventInterface
{
	protected DateTimeImmutable $occur;

	protected CategoryId $categoryId;

	public function __construct(CategoryId $categoryId)
	{
		$this->categoryId = $categoryId;

		$this->occur = new DateTimeImmutable();
	}

	public function getCategoryId(): CategoryId
	{
		return $this->categoryId;
	}

	public function getOccur(): DateTimeImmutable
	{
		return $this->occur;
	}
}
