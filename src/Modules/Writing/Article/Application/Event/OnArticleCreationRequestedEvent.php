<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class OnArticleCreationRequestedEvent extends Event
{
	private string $title;
	private string $description;
	private string $content;

	public function __construct(string $title, string $description, string $content)
	{
		$this->title = $title;
		$this->description = $description;
		$this->content = $content;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getDescription(): string
	{
		return $this->description;
	}
	
	public function getContent(): string
	{
		return $this->content;
	}
}
