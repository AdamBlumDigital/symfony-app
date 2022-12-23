<?php

declare(strict_types=1);

namespace App\Modules\Writing\Category\Application\Service;

use App\Modules\Writing\Category\Application\Model\FindCategoryQuery;
use App\Modules\Writing\Category\Domain\Repository\CategoryRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class FindCategoryHandler
{
	private CategoryRepositoryInterface $categoryRepository;

	public function __construct(CategoryRepositoryInterface $categoryRepository)
	{
		$this->categoryRepository = $categoryRepository;
	}

	public function __invoke(FindCategoryQuery $findCategoryQuery): mixed
	{
		$categoryId = $findCategoryQuery->getCategoryId();

		$category = $this->categoryRepository->find($categoryId);

		return $category;
	}


}
