<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $containerConfigurator): void {
	$containerConfigurator->extension('doctrine_migrations', [
		'migrations_paths' => [
			'DoctrineMigrations' => '%kernel.project_dir%/migrations'
		], 
		'enable_profiler' => false
	]);
};
