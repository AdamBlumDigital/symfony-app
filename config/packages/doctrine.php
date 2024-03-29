<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $containerConfigurator): void {
	$containerConfigurator->extension('doctrine', [
		'dbal' => [
			'url' => env('DATABASE_URL')->resolve()
		], 
		'orm' => [
			'auto_generate_proxy_classes' => true, 
			'naming_strategy' => 'doctrine.orm.naming_strategy.underscore_number_aware', 
			'auto_mapping' => true, 
			'mappings' => [
				'Article' => [
					'type' => 'xml', 
					'is_bundle' => false, 
					'dir' => '%kernel.project_dir%/src/Modules/Writing/Article/Infrastructure/DoctrineMapping', 
					'prefix' => 'App\Modules\Writing\Article\Domain\Entity', 
					'alias' => 'Article'
				], 
				'Category' => [
					'type' => 'xml', 
					'is_bundle' => false, 
					'dir' => '%kernel.project_dir%/src/Modules/Writing/Category/Infrastructure/DoctrineMapping', 
					'prefix' => 'App\Modules\Writing\Category\Domain\Entity', 
					'alias' => 'Category'
				]
			]
		]
	]);
    if ($containerConfigurator->env() === 'test') {
		$containerConfigurator->extension('doctrine', [
			'dbal' => [
				'dbname_suffix' => '_test%env(default::TEST_TOKEN)%'
			]
		]);
    }
    if ($containerConfigurator->env() === 'prod') {
		$containerConfigurator->extension('doctrine', [
			'orm' => [
				'auto_generate_proxy_classes' => false, 
				'query_cache_driver' => [
					'type' => 'pool', 
					'pool' => 'doctrine.system_cache_pool'
				], 
				'result_cache_driver' => [
					'type' => 'pool', 
					'pool' => 'doctrine.result_cache_pool'
				]
			]
		]);
		$containerConfigurator->extension('framework', [
			'cache' => [
				'pools' => [
					'doctrine.result_cache_pool' => [
						'adapter' => 'cache.adapter.apcu'
					], 
					'doctrine.system_cache_pool' => [
						'adapter' => 'cache.adapter.apcu'
					]
				]
			]
		]);
    }
};
