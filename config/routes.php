<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routingConfigurator): void {
    $routingConfigurator->import('routes/api.php')
        ->prefix('/{_locale}/api')
        ->defaults(['_locale' => '%app.locales_default%'])
        ->requirements(['_locale' => '%app.locales_supported%']);

    $routingConfigurator->import('routes/view.php')
        ->prefix('/{_locale}')
        ->defaults(['_locale' => '%app.locales_default%'])
        ->requirements(['_locale' => '%app.locales_supported%']);

    $routingConfigurator->import('../src/Kernel.php', 'annotation');
};
