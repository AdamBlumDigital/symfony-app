<?php

declare(strict_types=1);

namespace App\Shared\Application\Controller\View;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

final class ErrorController extends AbstractController
{

	public function __invoke(FlattenException $exception): Response
	{
		$message = $exception->getStatusText();
		$statusCode = $exception->getStatusCode();

		return $this->render('@Shared/view/error.html.twig', [
			'status_code' => $statusCode,
			'message' => $message
		]);
	}
}
