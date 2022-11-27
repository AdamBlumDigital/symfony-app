<?php

declare(strict_types=1);

namespace App\Modules\StaticPage\Application\Controller\View;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

final class HomepageController extends AbstractController
{
	public function __invoke(): Response
	{

		return $this->render('@StaticPage/view/homepage.html.twig');
	}
}
