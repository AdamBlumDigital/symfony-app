<?php

declare(strict_types=1);

namespace App\Modules\Writing\Category\Application\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Uid\Uuid;

use App\Modules\Writing\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Modules\Writing\Category\Application\Model\CreateCategoryCommand;
use App\Modules\Writing\Category\Domain\Entity\Category;
use App\Modules\Writing\Shared\Domain\Entity\ValueObject\CategoryId;

class CreateCategoryService implements MessageHandlerInterface
{
	private EventDispatcherInterface $eventDispatcher;
	private CategoryRepositoryInterface $categoryRepository;

	public function __construct(
		CategoryRepositoryInterface $categoryRepository,
		EventDispatcherInterface $eventDispatcher
	)
	{
		$this->categoryRepository = $categoryRepository;
		$this->eventDispatcher = $eventDispatcher;
	}

	public function __invoke(CreateCategoryCommand $createCategoryCommand): void 
	{
		$category = Category::create(
			new CategoryId(
				Uuid::v4()->jsonSerialize()	// Return UUID as string, not object
			),
			$createCategoryCommand->getTitle(),	
			$createCategoryCommand->getSlug(),	
			$createCategoryCommand->getDescription()
		);

		$this->categoryRepository->save($category);

		foreach ($category->pullDomainEvents() as $domainEvent) {
			$this->eventDispatcher->dispatch($domainEvent);
		}
	}
}
