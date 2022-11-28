<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $containerConfigurator): void {
	$containerConfigurator->extension('framework', [
		'default_locale' => 'en', 
		'translator' => [
			'default_path' => '%kernel.project_dir%/translations', 
			'paths' => [
				'%kernel.project_dir%/src/Shared/Resource/Translation/', 
				'%kernel.project_dir%/src/Modules/Writing/Article/Resource/Translation/'
			], 
			'fallbacks' => [
				'en'
			]
		]
	]);
};
