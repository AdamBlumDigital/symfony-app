<?php

declare(strict_types=1);

namespace App\Modules\Writing\Category\Application\Controller\View;

use App\Modules\Writing\Category\Application\Model\FindCategoryQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

final class GetCategoryController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(Uuid $id): Response
    {
        $categoryId = (string) $id;

        /** @var string $category */
        $category = $this->handle(new FindCategoryQuery($categoryId));

        return $this->render('@Category/view/single.html.twig', [
            'category' => $category,
        ]);
    }
}
