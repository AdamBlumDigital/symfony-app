<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\Controller\View;

use App\Modules\Module\Domain\Repository\ModuleRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;

final class GetAllModulesController extends AbstractController
{
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

	public function __invoke(): Response
	{
		$this->logger->info('<GetAllModulesController> Invoked');
		
		$modules = $this->moduleRepository->findAll();	

		return $this->render('view/module/all_modules.html.twig', [
			'modules' => $modules
		]);
	}
}
