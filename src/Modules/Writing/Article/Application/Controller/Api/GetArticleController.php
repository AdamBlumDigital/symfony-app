<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Controller\Api;

use App\Modules\Writing\Article\Application\Model\FindArticleQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

final class GetArticleController extends AbstractController
{
	use HandleTrait;

	private LoggerInterface $logger;
	private SerializerInterface $serializer;

	public function __construct(
		MessageBusInterface $messageBus,
		SerializerInterface $serializer,
		LoggerInterface $logger
	)
	{
		$this->messageBus = $messageBus;
		$this->serializer = $serializer;
		$this->logger = $logger;
	}

	public function __invoke(Uuid $id): JsonResponse
	{
		$this->logger->info('<GetArticleController> Invoked');

		$this->logger->info('<FindArticleQuery> will be handled');

		/** @var string $article */
		$article = $this->handle(new FindArticleQuery($id->__toString()));
		
		$result = $this->serializer->serialize($article, 'json');

		$this->logger->info('<FindArticleQuery> returned data');

		$this->logger->info('<GetArticleController> will respond');

		return JsonResponse::fromJsonString($result);
	}
}
