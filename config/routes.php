<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;

return static function (RoutingConfigurator $routingConfigurator): void {
	/**
	 *	API Routes (JSON)
	 */
	/*
    $routingConfigurator->import('routes/api.php')
        ->prefix('/{_locale}/api')
        ->defaults(['_locale' => '%app.locales_default%'])
        ->requirements(['_locale' => '%app.locales_supported%']);
	*/
	/**
	 *	View Routes (HTML)
	 */
    $routingConfigurator->import('routes/view.php')
        ->prefix('/{_locale}')
        ->defaults(['_locale' => '%app.locales_default%'])
        ->requirements(['_locale' => '%app.locales_supported%']);
	
	/**
     *  Redirect Home Page to proper locale
     */
    $routingConfigurator->add('homepage_redirect_locale', '/')
        ->controller(RedirectController::class)
        ->requirements([
            '_locale' => '%app.locales_supported%'
        ])
        ->defaults([
            'route' => 'view_homepage',
            '_locale' => '%app.locales_default%'
        ])
    ;

    $routingConfigurator->import('../src/Kernel.php', 'annotation');
};
