<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\Model;

final class CreateModuleCommand
{
	private string $title;

	public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
