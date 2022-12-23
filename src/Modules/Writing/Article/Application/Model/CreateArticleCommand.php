<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Model;

use App\Shared\Message\AsyncMessageInterface;

/**
 *	This message is handled via async transport.
 *
 *	config/packages/messenger.yaml
 */
final class CreateArticleCommand implements AsyncMessageInterface
{
	private string $title;
	private string $description;
	private string $content;
	private string $categoryId;
	private bool $isVisible;

	public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

	public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

	public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

	public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function setCategoryId(string $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

	public function getIsVisible(): bool
	{
		return $this->isVisible;
	}

	public function setIsVisible(bool $isVisible): void
	{
		$this->isVisible = $isVisible;
	}
}
