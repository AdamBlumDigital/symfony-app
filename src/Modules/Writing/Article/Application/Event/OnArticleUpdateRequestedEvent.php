<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class OnArticleUpdateRequestedEvent extends Event
{
	private string $id;
	private string $title;
	private string $description;
	private string $content;
	private string $categoryId;

	public function __construct(
		string $id,
		string $title, 
		string $description, 
		string $content,
		string $categoryId,
	)
	{
		$this->id = $id;
		$this->title = $title;
		$this->description = $description;
		$this->content = $content;
		$this->categoryId = $categoryId;
	}
	
	public function getId(): string
	{
		return $this->id;
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
}
