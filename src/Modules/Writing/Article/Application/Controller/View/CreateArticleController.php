<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Controller\View;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Uid\Uuid;
use App\Modules\Writing\Article\Application\Form\ArticleType;
use App\Modules\Writing\Article\Application\Model\CreateArticleCommand;
use App\Modules\Writing\Article\Domain\Entity\Article;
use App\Modules\Writing\Article\Application\Model\CreateArticleRequest;
use App\Modules\Writing\Article\Domain\Entity\ArticleId;
use App\Modules\Writing\Article\Application\Event\OnArticleCreationRequestedEvent;

final class CreateArticleController extends AbstractController
{
	private EventDispatcherInterface $eventDispatcher;

	public function __construct(
		EventDispatcherInterface $eventDispatcher,
	)
	{
		$this->eventDispatcher = $eventDispatcher;
	}

	public function __invoke(Request $request): Response
	{
		/*
		 *	Going to try and speed things up by not associating
		 *	an entity with a form. Since API requests won't be validated
		 *	through forms regardless, we should start with a regular
		 *	array and work with both form and JSON data identically
		 */
		$articleForm = $this->createForm(ArticleType::class);

		/**
		 *	90% of this controller's processing time
		 *	occurs right here in the handleRequest()
		 *	method. Unsure of how to optimize this.
		 */
		$articleForm->handleRequest($request);

		if ($articleForm->isSubmitted() && $articleForm->isValid()) {
			$articleData = $articleForm->getData();
			
			$this->addFlash('notice', 'Your Article Request is being sent.');

			$this->eventDispatcher->dispatch(new OnArticleCreationRequestedEvent(
				$articleData['title'],
				$articleData['description'],
				$articleData['content'],
				$articleData['category']->getId()->getValue(),
			));
			return $this->redirectToRoute('view_get_some_articles');	
		}

		return $this->render('@Article/view/create.html.twig', [
			'articleForm'	=> $articleForm->createView()
		]);
	}
}
