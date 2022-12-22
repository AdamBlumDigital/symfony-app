<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $containerConfigurator): void {
	$containerConfigurator->extension('twig', [
		'default_path' => '%kernel.project_dir%/templates', 
		'globals' => [
			'color_scheme' => 'light dark', 
			'template' => [
				'default' => '@Shared/base.html.twig'
			], 
			'layout' => [
				'default' => '@Shared/layout/default.html.twig', 
				'sidebar' => '@Shared/layout/sidebar.html.twig', 
			]
		], 
		'form_themes' => [
			'@Shared/form_theme/default.html.twig'
		], 
		'paths' => [
			'src/Shared/Resource/View/Twig' => 'Shared', 
			'src/Modules/StaticPage/Resource/View/Twig' => 'StaticPage', 
			'src/Modules/Writing/Article/Resource/View/Twig' => 'Article', 
			'src/Modules/Writing/Category/Resource/View/Twig' => 'Category',
			'src/Modules/Writing/Shared/Resource/View/Twig' => 'Writing', 
		]
	]);
    if ($containerConfigurator->env() === 'test') {
		$containerConfigurator->extension('twig', [
			'strict_variables' => true
		]);
    }
};
