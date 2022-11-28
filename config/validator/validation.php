<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
	$containerConfigurator->extension('App\Modules\Writing\Article\Domain\Entity\Article', [
		'constraints' => [
			['Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity' => 'title']
		], 
		'properties' => [
			'title' => [
				['NotBlank' => null]
			], 
			'description' => [
				['NotBlank' => null]
			]
		]
	]);
};
