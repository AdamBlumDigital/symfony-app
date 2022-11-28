<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $containerConfigurator): void {
	$containerConfigurator->extension('framework', [
		'messenger' => [
			'failure_transport' => 'failed', 
			'transports' => [
				'async' => env('MESSENGER_TRANSPORT_DSN'), 
				'failed' => 'doctrine://default?queue_name=failed'
			], 'routing' => [
				'App\Shared\Message\AsyncMessageInterface' => 'async'
			]
		]
	]);
};
