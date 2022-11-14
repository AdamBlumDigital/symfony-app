<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GetModuleController extends AbstractController
{
	public function __invoke(): JsonResponse
	{
		return new JsonResponse(['title' => 'GetModuleController']);
	}
}
