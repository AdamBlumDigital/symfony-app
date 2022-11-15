<?php

declare(strict_types=1);

namespace App\Modules\Module\Infrastructure\Repository;

use App\Modules\Module\Domain\Entity\Module;
use App\Modules\Module\Domain\Repository\ModuleRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Module>
 * @method Module|null find($id, $lockMode = null, $lockVersion = null)
 * @method Module|null findOneBy(array $criteria, array $orderBy = null)
 * @method Module[]    findAll()
 * @method Module[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleRepository extends ServiceEntityRepository implements ModuleRepositoryInterface
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Module::class);
	}

	public function save(Module $module): void
	{
		$this->_em->persist($module);
		$this->_em->flush();
	}
}
