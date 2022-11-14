<?php

declare(strict_types=1);

namespace App\Modules\Module\Domain\Entity;

use App\Shared\Aggregate\AggregateRoot;
use App\Modules\Module\Domain\Entity\ModuleId;
use App\Modules\Module\Domain\Event\ModuleCreatedEvent;

class Module extends AggregateRoot
{
	private string $id;

	private string $title;

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

	public static function create(ModuleId $moduleId, string $title): self
	{
		$module = new self($moduleId);
		$module->setTitle($title);

		$module->recordDomainEvent(new ModuleCreatedEvent($moduleId));

		return $module;
	}
}
