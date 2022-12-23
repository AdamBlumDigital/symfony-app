<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Controller\View;

use App\Modules\Writing\Article\Application\Model\FindSomeArticlesQuery;
// use App\Modules\Writing\Category\Domain\Repository\CategoryRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class GetSomeArticlesController extends AbstractController
{
    use HandleTrait;

    /** see GetArticleController for details */
    /** @phpstan-ignore-next-line **/
    private MessageBusInterface $messageBus;
    //	private CategoryRepositoryInterface $categoryRepository;

    public function __construct(
        MessageBusInterface $messageBus,
//		CategoryRepositoryInterface $categoryRepository
    ) {
        $this->messageBus = $messageBus;
        //		$this->categoryRepository = $categoryRepository;
    }

    public function __invoke(int $page = 1, int $size = 2): Response
    {
        //		$categories = $this->categoryRepository->findAll();

        $articles = $this->handle(new FindSomeArticlesQuery($page, $size));

        $pages = max(ceil(count($articles) / $size), 1);

        if ($page > $pages) {
            throw $this->createNotFoundException('Invalid page');
        }

        return $this->render('@Article/view/index.html.twig', [
//			'categories' => $categories,
            'articles' => $articles,
            'page' => $page,
            'size' => $size,
            'pages' => $pages,
        ]);
    }
}
