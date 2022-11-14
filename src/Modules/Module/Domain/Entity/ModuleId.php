<?php

declare(strict_types=1);

namespace App\Modules\Module\Domain\Entity;

use App\Shared\ValueObject\AggregateRootId;

final class ModuleId extends AggregateRootId
{
	protected string $uuid;

	public function __construct(string $uuid)
	{
		$this->uuid = $uuid;
	}

	public function getValue(): string
	{
		return $this->uuid;
	}
}
