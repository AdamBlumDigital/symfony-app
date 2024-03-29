<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $containerConfigurator): void {
	$containerConfigurator->extension('framework', [
		'notifier' => [
			'channel_policy' => [
				'urgent' => ['browser'], 
				'high' => ['browser'], 
				'medium' => ['browser'], 
				'low' => ['browser']
			], 
			'admin_recipients' => [
				['email' => 'admin@example.com']
			]
		]
	]);
};
