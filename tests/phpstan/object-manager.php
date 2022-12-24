<?php

declare(strict_types=1);

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

require dirname(__DIR__).'/../vendor/autoload.php';

(new Dotenv())->bootEnv(__DIR__ . '/../../.env');

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG'] );
$kernel->boot();

/** @var ContainerInterface $container */
$container = $kernel->getContainer();

/** @var ?Doctrine\Bundle\DoctrineBundle\Registry $doctrineService */
$doctrineService = $container->get('doctrine');

if ($doctrineService === null) {
	throw new \Exception('Doctrine Service not found');
}

/** @var EntityManager $objectManager */
$objectManager = $doctrineService->getManager();

return $objectManager;
