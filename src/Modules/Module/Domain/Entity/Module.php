<?php

declare(strict_types=1);

namespace App\Modules\Module\Domain\Entity;

use App\Shared\Aggregate\AggregateRoot;
use App\Modules\Module\Domain\Entity\ModuleId;
use App\Modules\Module\Domain\Event\ModuleCreatedEvent;
use DateTimeImmutable;

class Module extends AggregateRoot
{
	private string $id;

	private string $title;

	private DateTimeImmutable $createdAt;

	private DateTimeImmutable $updatedAt;

	public function __construct(ModuleId $id)
	{
		$this->id = $id->getValue();
	}

	public function getId(): ?ModuleId
	{
		return new ModuleId($this->id);
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
		ModuleId $moduleId, 
		string $title
	): self
	{
		$module = new self($moduleId);
		$module->setTitle($title);
		$module->setCreatedAt(new DateTimeImmutable('now'));
		$module->setUpdatedAt(new DateTimeImmutable('now'));

		$module->recordDomainEvent(new ModuleCreatedEvent($moduleId));

		return $module;
	}
}
