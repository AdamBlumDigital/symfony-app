<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $containerConfigurator): void {
	$containerConfigurator->extension('security', [
		'password_hashers' => [
			\Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface::class => 'auto'
		], 
		'providers' => [
			'users_in_memory' => [
				'memory' => null
			]
		], 
		'firewalls' => [
			'dev' => [
				'pattern' => '^/(_(profiler|wdt)|css|images|js)/', 
				'security' => false
			], 
			'main' => [
				'lazy' => true, 
				'provider' => 'users_in_memory'
			]
		], 
		'access_control' => null
	]);
    if ($containerConfigurator->env() === 'test') {
		$containerConfigurator->extension('security', [
			'password_hashers' => [
				\Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface::class => [
					'algorithm' => 'auto', 
					'cost' => 4, 
					'time_cost' => 3, 
					'memory_cost' => 10
				]
			]
		]);
    }
};
