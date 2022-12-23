<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class OnArticleCreationRequestedEvent extends Event
{
	private string $title;
	private string $description;
	private string $content;
	private string $categoryId;
	private bool $isVisible;

	public function __construct(
		string $title, 
		string $description, 
		string $content,
		string $categoryId,
		bool $isVisible
	)
	{
		$this->title = $title;
		$this->description = $description;
		$this->content = $content;
		$this->categoryId = $categoryId;
		$this->isVisible = $isVisible;
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
	
	public function getCategoryId(): string
	{
		return $this->categoryId;
	}

	public function getIsVisible(): bool
	{
		return $this->isVisible;
	}
}
