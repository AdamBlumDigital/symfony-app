<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Controller\View;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Modules\Writing\Article\Application\Form\ArticleType;
use App\Modules\Writing\Article\Application\Model\CreateArticleCommand;

final class CreateArticleController extends AbstractController
{
	public function __invoke(Request $request): Response
	{
		$createArticleCommand = new CreateArticleCommand();

		/*
		 *	Just trying some things out
		 */
		$articleForm = $this->createForm(ArticleType::class, $createArticleCommand, [
			'action' => $this->generateUrl('view_post_article')
		]);

		return $this->render('@Article/view/create.html.twig', [
			'articleForm'	=> $articleForm->createView()
		]);
	}
}
