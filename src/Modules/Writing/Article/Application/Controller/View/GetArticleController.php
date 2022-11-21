<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Controller\View;

use App\Modules\Writing\Article\Application\Model\FindArticleQuery;
use App\Modules\Writing\Article\Application\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

final class GetArticleController extends AbstractController
{
	use HandleTrait;

	private LoggerInterface $logger;

	public function __construct(
		MessageBusInterface $messageBus,
		LoggerInterface $logger
	)
	{
		$this->messageBus = $messageBus;
		$this->logger = $logger;
	}

	public function __invoke(Uuid $id): Response
	{
		$this->logger->info('<GetArticleController> Invoked');

		$this->logger->info('<FindArticleQuery> will be handled');

		/** @var string $article */
		$article = $this->handle(new FindArticleQuery($id->__toString()));

		$this->logger->info('<FindArticleQuery> returned data');

		$this->logger->info('<GetArticleController> will respond');

		/*
		 *	Just trying some things out
		 */
		$articleForm = $this->createForm(ArticleType::class, $article, [
			'action' => $this->generateUrl('view_post_article')
		]);

		return $this->render('@Article/view/single.html.twig', [
			'article' => $article,
			'articleForm'	=> $articleForm->createView()
		]);
	}
}
