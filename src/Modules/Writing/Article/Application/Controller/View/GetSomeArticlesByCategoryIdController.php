<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Controller\View;

use App\Modules\Writing\Article\Application\Model\FindSomeArticlesByCategoryQuery;
use App\Modules\Writing\Category\Application\Model\FindCategoryQuery;
use App\Modules\Writing\Category\Domain\Repository\CategoryRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

use function \is_countable;

final class GetSomeArticlesByCategoryIdController extends AbstractController
{
    use HandleTrait;

    /** see GetArticleController for details */
    /** @phpstan-ignore-next-line **/
    private MessageBusInterface $messageBus;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(
        MessageBusInterface $messageBus,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->messageBus = $messageBus;
        $this->categoryRepository = $categoryRepository;
    }

    public function __invoke(Uuid $id, int $page = 1, int $size = 1): Response
    {
        $categoryId = (string) $id;

        $categories = $this->categoryRepository->findAll();

        $category = $this->handle(new FindCategoryQuery($categoryId));

        $articles = $this->handle(new FindSomeArticlesByCategoryQuery($categoryId, $page, $size));

        if (!is_countable($articles)) {
            throw $this->createNotFoundException('Invalid page');
        }

        $pages = max(ceil(count($articles) / $size), 1);

        if ($page > $pages || null === $category) {
            throw $this->createNotFoundException('Invalid page');
        }

        return $this->render('@Article/view/index.html.twig', [
            'categories' => $categories,
            'category' => $category,
            'articles' => $articles,
            'page' => $page,
            'size' => $size,
            'pages' => $pages,
        ]);
    }
}
