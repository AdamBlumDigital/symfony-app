<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Domain\Entity;

use App\Shared\Aggregate\AggregateRoot;
use App\Modules\Writing\Article\Domain\Entity\ArticleId;
use App\Modules\Writing\Article\Domain\Event\ArticleCreatedEvent;
use App\Modules\Writing\Shared\Domain\Entity\ValueObject\CategoryId;
use DateTimeImmutable;

class Article extends AggregateRoot
{
	private string $id;

	private string $title;
	
	private string $description;
	
	private ?string $content = null;
	
//	private ?string $category = null;

	private DateTimeImmutable $createdAt;

	private DateTimeImmutable $updatedAt;

	public function __construct(ArticleId $id)
	{
		$this->id = $id->getValue();
	}

	public function getId(): ?ArticleId
	{
		return new ArticleId($this->id);
	}

/*	public function getCategory(): CategoryId
	{
		return new CategoryId($this->category);
	}

	public function setCategory(CategoryId $category): self
	{
		$this->category = $category;

		return $this;
	}
 */
	public function getTitle(): string
	{
		return $this->title;
	}

	public function setTitle(string $title): self
	{
		$this->title = $title;

		return $this;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function setDescription(string $description): self
	{
		$this->description = $description;

		return $this;
	}

	public function getContent(): ?string
	{
		return $this->content;
	}

	public function setContent(string $content): self
	{
		$this->content = $content;

		return $this;
	}

	public function getCreatedAt(): ?DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function setCreatedAt(DateTimeImmutable $createdAt): self
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	public function getUpdatedAt(): ?DateTimeImmutable
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt(DateTimeImmutable $updatedAt): self
	{
		$this->updatedAt = $updatedAt;

		return $this;
	}

	public static function create(
		ArticleId $articleId, 
		string $title,
		string $description,
		string $content,
	//	string $category
	): self
	{
		$article = new self($articleId);
		$article->setTitle($title);
		$article->setDescription($description);
		$article->setContent($content);
	//	$article->setCategory($category);
		$article->setCreatedAt(new DateTimeImmutable('now'));
		$article->setUpdatedAt(new DateTimeImmutable('now'));

		$article->recordDomainEvent(new ArticleCreatedEvent($articleId));

		return $article;
	}
}
