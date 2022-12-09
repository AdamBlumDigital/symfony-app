<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Controller\View;

use App\Modules\Writing\Article\Domain\Repository\ArticleRepositoryInterface;
use App\Modules\Writing\Category\Domain\Repository\CategoryRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class GetSomeArticlesByCategoryIdController extends AbstractController
{
	private ArticleRepositoryInterface $articleRepository;
	private CategoryRepositoryInterface $categoryRepository;

	public function __construct(
		ArticleRepositoryInterface $articleRepository,
		CategoryRepositoryInterface $categoryRepository
	)
	{
		$this->articleRepository = $articleRepository;
		$this->categoryRepository = $categoryRepository;
	}

	public function __invoke(Uuid $id, int $page = 1, int $size = 3): Response
	{

		$category = $this->categoryRepository->find($id->__toString());
		
		$articles = $this->articleRepository->findSomeByCategoryId($id->__toString(), $page, $size);

		return $this->render('@Article/view/index_by_category.html.twig', [
			'category' => $category,
			'articles' => $articles,
			'page'	=> $page,
			'size'	=> $size,
			'pages'	=> ceil( count($articles) / $size )
		]);
	}
}
