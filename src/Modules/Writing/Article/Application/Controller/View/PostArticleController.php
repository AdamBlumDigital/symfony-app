<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Controller\View;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Modules\Writing\Article\Application\Form\ArticleType;
use App\Modules\Writing\Article\Application\Model\CreateArticleCommand;
use App\Modules\Writing\Article\Application\Event\OnArticleCreationRequestedEvent;

final class PostArticleController extends AbstractController
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
		$createArticleCommand = new CreateArticleCommand();
		$articleForm = $this->createForm(ArticleType::class, $createArticleCommand);

		$articleForm->handleRequest($request);

		if ($articleForm->isSubmitted() && $articleForm->isValid()) {
			$articleData = $articleForm->getData();

			$this->addFlash('notice', 'Your Article Request is being sent.');

			$this->eventDispatcher->dispatch(new OnArticleCreationRequestedEvent(
				$articleData->getTitle(),
				$articleData->getDescription()
			));
		}

		return $this->redirectToRoute('view_get_all_articles');	
	}

}
