<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Controller\View;

use App\Modules\Writing\Article\Domain\Repository\ArticleRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

final class GetAllArticlesController extends AbstractController
{
	private LoggerInterface $logger;
	private ArticleRepositoryInterface $articleRepository;

	public function __construct(
		LoggerInterface $logger,
		ArticleRepositoryInterface $articleRepository
	)
	{
		$this->logger = $logger;
		$this->articleRepository = $articleRepository;
	}

	public function __invoke(): Response
	{
		$this->logger->info('<GetAllArticlesController> Invoked');
		
		$articles = $this->articleRepository->findAll();	

		return $this->render('@Article/view/list.html.twig', [
			'articles' => $articles
		]);
	}
}
