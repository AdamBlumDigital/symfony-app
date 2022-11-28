<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routingConfigurator): void {
    $routingConfigurator->add('view_homepage', '/')
        ->controller(App\Modules\StaticPage\Application\Controller\View\HomepageController::class);

    $routingConfigurator->add('view_get_some_articles', '/writing/{page}')
        ->controller(App\Modules\Writing\Article\Application\Controller\View\GetSomeArticlesController::class)
        ->defaults(['page' => 1])
        ->methods(['GET'])
        ->requirements(['page' => 'd+']);

    $routingConfigurator->add('view_get_all_articles', '/writing/view/all')
        ->controller(App\Modules\Writing\Article\Application\Controller\View\GetAllArticlesController::class)
        ->methods(['GET']);

    $routingConfigurator->add('view_get_article', '/writing/view/{id}')
        ->controller(App\Modules\Writing\Article\Application\Controller\View\GetArticleController::class)
        ->methods(['GET']);

    $routingConfigurator->add('view_create_article', '/writing/new')
        ->controller(App\Modules\Writing\Article\Application\Controller\View\CreateArticleController::class)
        ->methods(['GET', 'POST']);

    $routingConfigurator->add('view_post_article', '/writing/post')
        ->controller(App\Modules\Writing\Article\Application\Controller\View\PostArticleController::class)
        ->methods(['POST']);

    $routingConfigurator->add('view_get_category', '/writing/topic/{id}/{page}')
        ->controller(App\Modules\Writing\Article\Application\Controller\View\GetSomeArticlesByCategoryIdController::class)
        ->defaults(['page' => 1])
        ->methods(['GET'])
        ->requirements(['page' => 'd+']);
};
