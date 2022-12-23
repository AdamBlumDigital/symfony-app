<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set('app.locales_supported', 'en|es');

    $parameters->set('app.locales_default', 'en');

    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\', __DIR__ . '/../src/')
		->exclude([
			__DIR__ . '/../src/DependencyInjection/', 
			__DIR__ . '/../src/Modules/*/Domain/Entity/', 
			__DIR__ . '/../src/Modules/Writing/*/Domain/Entity/', 
			__DIR__ . '/../src/Kernel.php'
		]);
};
