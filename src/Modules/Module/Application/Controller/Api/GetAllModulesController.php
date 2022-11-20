<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\Controller\Api;

use App\Modules\Module\Domain\Repository\ModuleRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;

final class GetAllModulesController extends AbstractController
{
	use HandleTrait;

	private LoggerInterface $logger;
	private ModuleRepositoryInterface $moduleRepository;
	private SerializerInterface $serializer;

	public function __construct(
		LoggerInterface $logger,
		SerializerInterface $serializer,
		ModuleRepositoryInterface $moduleRepository
	)
	{
		$this->logger = $logger;
		$this->serializer = $serializer;
		$this->moduleRepository = $moduleRepository;
	}

	public function __invoke(): JsonResponse
	{
		$this->logger->info('<GetAllModulesController> Invoked');
		
		$modules = $this->moduleRepository->findAll();	

		$result = $this->serializer->serialize($modules, 'json');

		$this->logger->info('<GetAllModulesController> will respond');

		return JsonResponse::fromJsonString($result);
	}
}
