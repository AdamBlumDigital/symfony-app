<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Service;

use App\Modules\Writing\Article\Application\Model\CreateArticleCommand;
use App\Modules\Writing\Article\Domain\Entity\Article;
use App\Modules\Writing\Article\Domain\Entity\ArticleId;
use App\Modules\Writing\Article\Domain\Repository\ArticleRepositoryInterface;
use App\Modules\Writing\Category\Domain\Repository\CategoryRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
final class CreateArticleHandler
{
    private ArticleRepositoryInterface $articleRepository;
    private CategoryRepositoryInterface $categoryRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        CategoryRepositoryInterface $categoryRepository,
        EventDispatcherInterface $eventDispatcher,
    ) {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(CreateArticleCommand $createArticleCommand): void
    {
        $categoryObject = $this->categoryRepository->findOneBy(['id' => $createArticleCommand->getCategoryId()]);
        $article = Article::create(
            new ArticleId(
                Uuid::v7()->jsonSerialize()	// Return UUID as string, not object
            ),
            $createArticleCommand->getTitle(),
            $createArticleCommand->getDescription(),
            $createArticleCommand->getContent(),
            $categoryObject,
            $createArticleCommand->getIsVisible()
        );

        $this->articleRepository->save($article);

        /*
         *	Do not call pullDomainEvents() more than once
         */
        foreach ($article->pullDomainEvents() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }
    }
}
