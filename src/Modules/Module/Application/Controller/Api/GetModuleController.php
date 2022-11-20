<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\Controller\Api;

use App\Modules\Module\Application\Model\FindModuleQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

final class GetModuleController extends AbstractController
{
	use HandleTrait;

	private LoggerInterface $logger;
	private SerializerInterface $serializer;

	public function __construct(
		MessageBusInterface $messageBus,
		SerializerInterface $serializer,
		LoggerInterface $logger
	)
	{
		$this->messageBus = $messageBus;
		$this->serializer = $serializer;
		$this->logger = $logger;
	}

	public function __invoke(Uuid $id): JsonResponse
	{
		$this->logger->info('<GetModuleController> Invoked');

		$this->logger->info('<FindModuleQuery> will be handled');

		/** @var string $module */
		$module = $this->handle(new FindModuleQuery($id->__toString()));
		
		$result = $this->serializer->serialize($module, 'json');

		$this->logger->info('<FindModuleQuery> returned data');

		$this->logger->info('<GetModuleController> will respond');

		return JsonResponse::fromJsonString($result);
	}
}
