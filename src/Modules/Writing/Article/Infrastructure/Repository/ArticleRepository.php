<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Infrastructure\Repository;

use App\Modules\Writing\Article\Domain\Entity\Article;
use App\Modules\Writing\Article\Domain\Repository\ArticleRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Article>
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository implements ArticleRepositoryInterface
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Article::class);
	}

	public function findSome(int $page = 1, int $length = 2): Paginator
	{
		$query = $this->createQueryBuilder('a')->getQuery();

		$paginator = new Paginator($query);

		$paginator->getQuery()
			->setFirstResult($length * ($page - 1) )
			->setMaxResults($length)
		;

		return $paginator;

	}	

	public function findSomeByCategoryId(string $categoryId, int $page = 1, int $length = 2): Paginator
	{
		$query = $this->createQueryBuilder('a')
			->where('a.category = :categoryId')
			->setParameter('categoryId', $categoryId)
			->getQuery()
		;

		$paginator = new Paginator($query);

		$paginator->getQuery()
			->setFirstResult($length * ($page - 1) )
			->setMaxResults($length)
		;

		return $paginator;

	}	

	public function save(Article $article): void
	{
		$this->_em->persist($article);
		$this->_em->flush();
	}
}
