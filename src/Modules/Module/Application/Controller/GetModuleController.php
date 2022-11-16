<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\Controller;

use App\Modules\Module\Application\Model\FindModuleQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Psr\Log\LoggerInterface;

final class GetModuleController extends AbstractController
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

	public function __invoke(string $id): JsonResponse
	{
		$this->logger->info('<GetModuleController> Invoked');

		$this->logger->info('<FindModuleQuery> will be handled');

		/** @var string $module */
		$module = $this->handle(new FindModuleQuery($id));

		$this->logger->info('<FindModuleQuery> returned data');

		$this->logger->info('<GetModuleController> will respond');

		return JsonResponse::fromJsonString($module);
	}
}
