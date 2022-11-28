<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $containerConfigurator): void {
	$containerConfigurator->extension('framework', [
		'validation' => [
			'email_validation_mode' => 'html5'
		]
	]);
    if ($containerConfigurator->env() === 'test') {
		$containerConfigurator->extension('framework', [
			'validation' => [
				'not_compromised_password' => false
			]
		]);
    }
};
