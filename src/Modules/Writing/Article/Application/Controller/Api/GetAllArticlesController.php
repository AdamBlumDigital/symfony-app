<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Controller\Api;

use App\Modules\Writing\Article\Domain\Repository\ArticleRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;

final class GetAllArticlesController extends AbstractController
{
	use HandleTrait;

	private LoggerInterface $logger;
	private ArticleRepositoryInterface $articleRepository;
	private SerializerInterface $serializer;

	public function __construct(
		LoggerInterface $logger,
		SerializerInterface $serializer,
		ArticleRepositoryInterface $articleRepository
	)
	{
		$this->logger = $logger;
		$this->serializer = $serializer;
		$this->articleRepository = $articleRepository;
	}

	public function __invoke(): JsonResponse
	{
		$this->logger->info('<GetAllArticlesController> Invoked');
		
		$articles = $this->articleRepository->findAll();	

		$result = $this->serializer->serialize($articles, 'json');

		$this->logger->info('<GetAllArticlesController> will respond');

		return JsonResponse::fromJsonString($result);
	}
}
