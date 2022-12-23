<?php

declare(strict_types=1);

namespace App\Modules\StaticPage\Application\Controller\View;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class ServicesController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('@StaticPage/view/services.html.twig', [
            'email' => 'adam@adamblum.digital',
            'available' => true,
        ]);
    }
}
