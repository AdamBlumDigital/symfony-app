<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\Requirement\Requirement;

return static function (RoutingConfigurator $routingConfigurator): void {
    $routingConfigurator->add('view_homepage', '/')
        ->controller(App\Modules\StaticPage\Application\Controller\View\HomepageController::class);

	$routingConfigurator->add('view_services', '/services')
        ->controller(App\Modules\StaticPage\Application\Controller\View\ServicesController::class);

    $routingConfigurator->add('view_get_some_articles', '/writing/{page}')
        ->controller(App\Modules\Writing\Article\Application\Controller\View\GetSomeArticlesController::class)
        ->defaults(['page' => 1])
        ->methods(['GET'])
        ->requirements(['page' => Requirement::POSITIVE_INT]);

    $routingConfigurator->add('view_get_article', '/writing/view/{id}')
        ->controller(App\Modules\Writing\Article\Application\Controller\View\GetArticleController::class)
        ->methods(['GET'])
		->requirements([
			'id' => Requirement::UUID
		]);

    $routingConfigurator->add('view_get_category', '/writing/topic/{id}/{page}')
        ->controller(App\Modules\Writing\Article\Application\Controller\View\GetSomeArticlesByCategoryIdController::class)
        ->defaults(['page' => 1])
        ->methods(['GET'])
		->requirements([
			'id' => Requirement::UUID,
			'page' => Requirement::POSITIVE_INT
		]);
};
