<?php

declare(strict_types=1);

namespace App\Modules\Module\Application\Controller;

use App\Modules\Module\Application\Event\OnCreationRequestedEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use json_decode;

final class PostModuleController extends AbstractController
{
	private EventDispatcherInterface $eventDispatcher;
	private LoggerInterface $logger;

	public function __construct(
		EventDispatcherInterface $eventDispatcher,
		LoggerInterface $logger
	)
	{
		$this->eventDispatcher = $eventDispatcher;
		$this->logger = $logger;
	}

	public function __invoke(/*Request $request*/ string $title): JsonResponse
	{
		/** @@@@var array{'title': string} $parameters */
		/*$parameters = json_decode(
			$request->getContent(),
        	true, 512,
        	JSON_THROW_ON_ERROR
		);*/

		$this->logger->info('<PostModuleController> Invoked');
		$this->logger->info('Going to dispatch new <OnCreationRequestedEvent>');
		$this->eventDispatcher->dispatch(new OnCreationRequestedEvent(
//			$parameters['title']
			$title
		));

		return new JsonResponse(
//			$request->getSession()->get('last_article_created')
		);
	}
}
