<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $containerConfigurator): void {
	$containerConfigurator->extension('framework', [
		'secret' => env('APP_SECRET')->string(), 
		'http_method_override' => false, 
		'error_controller' => 'App\Shared\Application\Controller\View\ErrorController',
		'http_client' => [
			'default_options' => [
				'headers' => [
					'X-Powered-By' => 'ACME App'
				]
			]
		], 
		'session' => [
			'handler_id' => null, 
			'cookie_secure' => 'auto', 
			'cookie_samesite' => 'lax', 
			'storage_factory_id' => 'session.storage.factory.native'
		], 
		'form' => [
			'csrf_protection' => [
				'field_name' => '_crsf_token'
			]
		], 
		'php_errors' => [
			'log' => true
		]
	]);
    if ($containerConfigurator->env() === 'test') {
		$containerConfigurator->extension('framework', [
			'test' => true, 
			'session' => [
				'storage_factory_id' => 'session.storage.factory.mock_file'
			]
		]);
    }
};
