<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\Service;

use App\Modules\Module\Application\Model\FindModuleQuery;
use App\Modules\Module\Domain\Repository\ModuleRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;

final class FindModuleHandler implements MessageHandlerInterface
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
		$this->logger->info('<FindModuleHandler> Invoked');

		$moduleId = $findModuleQuery->getModuleId();

		$this->logger->info(
			sprintf('<FindModuleHandler> looking for <Module> with ID <%s>', $moduleId)
		);

		$module = $this->moduleRepository->find($moduleId);	

		$result = $this->serializer->serialize($module, 'json');

		$this->logger->info(
			'<Module Repository> found Module: <' . $result . '>'
		);

		return $result;
	}


}
