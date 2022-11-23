<?php

declare(strict_types=1);

namespace App\Modules\Writing\Category\Domain\Entity;

use App\Shared\Aggregate\AggregateRoot;
use App\Modules\Writing\Shared\Domain\Entity\ValueObject\CategoryId;
use App\Modules\Writing\Category\Domain\Event\CategoryCreatedEvent;
use DateTimeImmutable;

class Category extends AggregateRoot
{
	private string $id;

	private string $title;
	
	private string $slug;
	
	private ?string $description = null;

	private DateTimeImmutable $createdAt;

	private DateTimeImmutable $updatedAt;

	public function __construct(CategoryId $id, string $title, string $slug)
	{
		$this->id = $id->getValue();
		$this->title = $title;
		$this->slug = $slug;
	}
	
	public function getId(): ?CategoryId
	{
		return new CategoryId($this->id);
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setTitle(string $title): self
	{
		$this->title = $title;

		return $this;
	}
	
	public function getSlug(): string
	{
		return $this->slug;
	}

	public function setSlug(string $slug): self
	{
		$this->slug = $slug;

		return $this;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(string $description): self
	{
		$this->description = $description;

		return $this;
	}
	
	public function getCreatedAt(): ?DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function setCreatedAt(DateTimeImmutable $createdAt): self
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	public function getUpdatedAt(): ?DateTimeImmutable
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt(DateTimeImmutable $updatedAt): self
	{
		$this->updatedAt = $updatedAt;

		return $this;
	}
	
	public static function create(
		CategoryId $categoryId, 
		string $title,
		string $slug
	): self
	{
		$category = new self($categoryId, $title, $slug);
		$category->setCreatedAt(new DateTimeImmutable('now'));
		$category->setUpdatedAt(new DateTimeImmutable('now'));

		$category->recordDomainEvent(new CategoryCreatedEvent($categoryId));

		return $category;
	}
}
