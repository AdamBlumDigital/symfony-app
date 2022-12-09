<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Model;

use App\Shared\Message\AsyncMessageInterface;

/**
 *	This message is handled via async transport.
 *
 *	config/packages/messenger.yaml
 */
final class UpdateArticleCommand implements AsyncMessageInterface
{
	private string $id;
	private string $title;
	private string $description;
	private string $content;
	private string $categoryId;
	
	public function getId(): string
    {
        return $this->id;
    }
	
	public function setId(string $id): void
    {
        $this->id = $id;
    }

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
}
