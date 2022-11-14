<?php

declare(strict_types=1);

namespace App\Modules\Module\Domain\Event;

use App\Modules\Module\Domain\Entity\ModuleId;
use App\Shared\Event\DomainEventInterface;
use Symfony\Contracts\EventDispatcher\Event;
use DateTimeImmutable;

final class ModuleCreatedEvent extends Event implements DomainEventInterface
{
	protected DateTimeImmutable $occur;

	protected ModuleId $moduleId;

	public function __construct(ModuleId $moduleId)
	{
		$this->moduleId = $moduleId;

		$this->occur = new DateTimeImmutable();
	}

	public function getModuleId(): ModuleId
	{
		return $this->moduleId;
	}

	public function getOccur(): DateTimeImmutable
	{
		return $this->occur;
	}
}
