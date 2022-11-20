<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\Controller\View;

use App\Modules\Module\Domain\Repository\ModuleRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

final class GetAllModulesController extends AbstractController
{
	private LoggerInterface $logger;
	private ModuleRepositoryInterface $moduleRepository;

	public function __construct(
		LoggerInterface $logger,
		ModuleRepositoryInterface $moduleRepository
	)
	{
		$this->logger = $logger;
		$this->moduleRepository = $moduleRepository;
	}

	public function __invoke(): Response
	{
		$this->logger->info('<GetAllModulesController> Invoked');
		
		$modules = $this->moduleRepository->findAll();	

		return $this->render('@Module/view/list.html.twig', [
			'modules' => $modules
		]);
	}
}
