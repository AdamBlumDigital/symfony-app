<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\Controller;

use App\Modules\Module\Application\Model\FindModuleQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class GetModuleController extends AbstractController
{
	use HandleTrait;

	public function __invoke(/*string $id*/): JsonResponse
	{
//		$module = $this->handle(new FindModuleQuery($id));
//		return JsonResponse::FromJsonString($module);
		return new JsonResponse(['title' => 'GetModuleController']);
	}
}
