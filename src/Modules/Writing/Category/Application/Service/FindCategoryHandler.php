<?php

declare(strict_types=1);

namespace App\Modules\Writing\Category\Application\Service;

use App\Modules\Writing\Category\Application\Model\FindCategoryQuery;
use App\Modules\Writing\Category\Domain\Repository\CategoryRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Psr\Log\LoggerInterface;

final class FindCategoryHandler implements MessageHandlerInterface
{
	private CategoryRepositoryInterface $categoryRepository;

	private LoggerInterface $logger;

	public function __construct(
		CategoryRepositoryInterface $categoryRepository, 
		LoggerInterface $logger
	)
	{
		$this->categoryRepository = $categoryRepository;
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
