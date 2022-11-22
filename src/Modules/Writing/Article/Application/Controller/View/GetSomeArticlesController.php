<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Controller\View;

use App\Modules\Writing\Article\Domain\Repository\ArticleRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

final class GetSomeArticlesController extends AbstractController
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

	public function __invoke(int $page = 1, int $size = 3): Response
	{
		$this->logger->info('<GetAllArticlesController> Invoked');
		
		$articles = $this->articleRepository->findSome($page, $size);	

		return $this->render('@Article/view/index.html.twig', [
			'articles' => $articles,
			'page'	=> $page,
			'size'	=> $size,
			'pages'	=> ceil( count($articles) / $size )
		]);
	}
}
