<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class OnCreationRequestedEvent extends Event
{
	private string $title;

	public function __construct(string $title)
	{
		$this->title = $title;
	}

	public function getTitle():string
	{
		return $this->title;
	}
}
