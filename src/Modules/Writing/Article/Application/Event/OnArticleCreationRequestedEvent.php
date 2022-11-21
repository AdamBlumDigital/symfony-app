<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class OnArticleCreationRequestedEvent extends Event
{
	private string $title;
	private string $description;

	public function __construct(string $title, string $description)
	{
		$this->title = $title;
		$this->description = $description;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getDescription(): string
	{
		return $this->description;
	}
}
