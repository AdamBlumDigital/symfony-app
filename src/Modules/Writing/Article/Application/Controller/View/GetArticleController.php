<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Controller\View;

use App\Modules\Writing\Article\Application\Model\FindArticleQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

final class GetArticleController extends AbstractController
{
	/**
	 * 	PHPStan 1.9.2 is incorrectly showing $messageBus as 
	 * 	'never read, only written', despite it being a 
	 * 	requirement of HandleTrait (which provides the handle()
	 * 	method in __invoke() to handle FindArticleQuery). This 
	 * 	was noted in a GitHub issue that was automatically closed:
	 *
	 * 	See https://github.com/phpstan/phpstan/issues/7509
	 */
	use HandleTrait;

	/** @phpstan-ignore-next-line **/
	private MessageBusInterface $messageBus;

	public function __construct(MessageBusInterface $messageBus)
	{
		$this->messageBus = $messageBus;
	}

	public function __invoke(Uuid $id): Response
	{
		/** @var string $article */
		$article = $this->handle(new FindArticleQuery($id->__toString()));

		return $this->render('@Article/view/single.html.twig', [
			'article' => $article
		]);
	}
}
