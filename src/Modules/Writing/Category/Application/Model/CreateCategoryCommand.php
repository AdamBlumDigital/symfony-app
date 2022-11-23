<?php

declare(strict_types=1);

namespace App\Modules\Writing\Category\Application\Model;

class CreateCategoryCommand
{
	private string $title;
	private string $slug;
	private ?string $description = null;

	public function __construct(string $title, string $slug, ?string $description = null)
	{
		$this->title = $title;
		$this->slug = $slug;
		$this->description = $description;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getSlug(): string
	{
		return $this->slug;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}
}
