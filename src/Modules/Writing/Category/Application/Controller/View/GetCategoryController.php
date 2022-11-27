<?php

declare(strict_types=1);

namespace App\Modules\Writing\Category\Application\Controller\View;

use App\Modules\Writing\Category\Application\Model\FindCategoryQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

final class GetCategoryController extends AbstractController
{
	use HandleTrait;
	private LoggerInterface $logger;

	public function __construct(
		MessageBusInterface $messageBus,
		LoggerInterface $logger
	)
	{
		$this->messageBus = $messageBus;
		$this->logger = $logger;
	}

	public function __invoke(Uuid $id): Response
	{
		$this->logger->info('<GetCategoryController> Invoked');

		$this->logger->info('<FindArticleQuery> will be handled');

		/** @var string $category */
		$category = $this->handle(new FindCategoryQuery($id->__toString()));

		$this->logger->info('<FindCategoryQuery> returned data');

		$this->logger->info('<GetCategoryController> will respond');

		return $this->render('@Category/view/single.html.twig', [
			'category' => $category
		]);
	}
}
