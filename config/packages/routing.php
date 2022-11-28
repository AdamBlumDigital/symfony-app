<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $containerConfigurator): void {
	$containerConfigurator->extension('framework', [
		'router' => [
			'utf8' => true
		]
	]);
    if ($containerConfigurator->env() === 'prod') {
		$containerConfigurator->extension('framework', [
			'router' => [
				'strict_requirements' => null
			]
		]);
    }
};
