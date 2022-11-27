<?php

declare(strict_types=1);

namespace App\Modules\Writing\Category\Application\Service;

use App\Modules\Writing\Category\Application\Model\FindCategoryQuery;
use App\Modules\Writing\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Modules\Writing\Article\Domain\Repository\ArticleRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Psr\Log\LoggerInterface;

final class FindCategoryHandler implements MessageHandlerInterface
{
	private CategoryRepositoryInterface $categoryRepository;
	private ArticleRepositoryInterface $articleRepository;

	private LoggerInterface $logger;

	public function __construct(
		CategoryRepositoryInterface $categoryRepository, 
		ArticleRepositoryInterface $articleRepository, 
		LoggerInterface $logger
	)
	{
		$this->categoryRepository = $categoryRepository;
		$this->articleRepository = $articleRepository;
		$this->logger = $logger;
	}

	public function __invoke(FindCategoryQuery $findCategoryQuery): mixed
	{
		$this->logger->info('<FindCategoryHandler> Invoked');

		$categoryId = $findCategoryQuery->getCategoryId();

		$this->logger->info(
			sprintf('<FindCategoryHandler> looking for <Category> with ID <%s>', $categoryId)
		);

		$category = $this->categoryRepository->find($categoryId);

		return $category;
	}


}
