<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routingConfigurator): void {
    $routingConfigurator->add('api_get_all_articles', '/writing/view/all')
        ->controller(App\Modules\Writing\Article\Application\Controller\Api\GetAllArticlesController::class)
        ->methods(['GET']);

    $routingConfigurator->add('api_get_article', '/writing/view/{id}')
        ->controller(App\Modules\Writing\Article\Application\Controller\Api\GetArticleController::class)
        ->methods(['GET']);

    $routingConfigurator->add('api_post_article', '/writing/create')
        ->controller(App\Modules\Writing\Article\Application\Controller\Api\PostArticleController::class)
        ->methods(['POST']);
};
