<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\Service;

use App\Modules\Module\Application\Model\FindModuleQuery;
use App\Modules\Module\Domain\Repository\ModuleRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use json_encode;

final class ModuleFinderHandler implements MessageHandlerInterface
{
	private ModuleRepositoryInterface $moduleRepository;

	private SerializerInterface $serializer;

	public function __construct(
		ModuleRepositoryInterface $moduleRepository, 
		SerializerInterface $serializer
	)
	{
		$this->moduleRepository = $moduleRepository;
		$this->serializer = $serializer;
	}

	public function __invoke(FindModuleQuery $findModuleQuery): string
	{
		$moduleId = $findModuleQuery->getModuleId();

		$module = $this->moduleRepository->find($moduleId);	
		$result = $this->serializer->serialize($module, 'json');
		return json_encode($result, JSON_THROW_ON_ERROR);
	}


}
