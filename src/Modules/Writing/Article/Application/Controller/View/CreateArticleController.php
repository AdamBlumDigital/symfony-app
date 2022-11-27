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
use App\Modules\Writing\Article\Domain\Entity\ArticleId;
use App\Modules\Writing\Article\Application\Event\OnArticleCreationRequestedEvent;

final class CreateArticleController extends AbstractController
{
	private EventDispatcherInterface $eventDispatcher;

	public function __construct(
		EventDispatcherInterface $eventDispatcher
	)
	{
		$this->eventDispatcher = $eventDispatcher;
	}

	public function __invoke(Request $request): Response
	{
		$article = new Article(
			new ArticleId(
				Uuid::v4()->jsonSerialize()
			)
		);

		/*
		 *	Just trying some things out
		 */
		$articleForm = $this->createForm(ArticleType::class, $article, [
		//	'action' => $this->generateUrl('view_post_article')
		]);

		$articleForm->handleRequest($request);


		if ($articleForm->isSubmitted() && $articleForm->isValid()) {
			$articleData = $articleForm->getData();

			$this->addFlash('notice', 'Your Article Request is being sent.');
			$this->addFlash('notice', json_encode($articleData));

			$this->eventDispatcher->dispatch(new OnArticleCreationRequestedEvent(
				$articleData->getTitle(),
				$articleData->getDescription(),
				$articleData->getContent(),
				$articleData->getCategory()->getId()->getValue()
			));
			return $this->redirectToRoute('view_get_some_articles');	
		}
		
		if ($articleForm->isSubmitted() && !$articleForm->isValid()) {
			$this->addFlash('notice', 'There were problems with your submission.');
		}

		return $this->render('@Article/view/create.html.twig', [
			'articleForm'	=> $articleForm->createView()
		]);
	}
}
