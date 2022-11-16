<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\Service;

use App\Modules\Module\Application\Model\FindModuleQuery;
use App\Modules\Module\Domain\Repository\ModuleRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;

final class ModuleFinderHandler implements MessageHandlerInterface
{
	private ModuleRepositoryInterface $moduleRepository;

	private SerializerInterface $serializer;

	private LoggerInterface $logger;

	public function __construct(
		ModuleRepositoryInterface $moduleRepository, 
		SerializerInterface $serializer,
		LoggerInterface $logger
	)
	{
		$this->moduleRepository = $moduleRepository;
		$this->serializer = $serializer;
		$this->logger = $logger;
	}

	public function __invoke(FindModuleQuery $findModuleQuery): string
	{
		$this->logger->info('<Module Finder Handler> Invoked');

		$moduleId = $findModuleQuery->getModuleId();

		$this->logger->info(
			'<Module Finder Handler> looking for <Module> with ID <' . $moduleId . '>'
		);

		$module = $this->moduleRepository->find($moduleId);	

		$result = $this->serializer->serialize($module, 'json');

		$this->logger->info(
			'<Module Repository> found Module: <' . $result . '>'
		);

		return $result;
	}


}
