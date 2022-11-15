<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\Model;

final class FindModuleQuery
{
	private string $moduleId;

	public function __construct(string $moduleId)
	{
		$this->moduleId = $moduleId;
	}

	public function getModuleId(): string
	{
		return $this->moduleId;
	}
}
