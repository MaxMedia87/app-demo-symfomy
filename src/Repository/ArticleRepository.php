<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Article[]
     */
    public function findLatestPublished(): array
    {
        return $this->published($this->latestWithAuthor())
            ->addSelect('c')
            ->leftJoin('a.comments', 'c')
            ->addSelect('t')
            ->leftJoin('a.tags', 't')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Article[]
     */
    public function findLatest(): array
    {
        return $this->latestWithAuthor()
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Article[]
     */
    public function findPublished(): array
    {
        return $this->published()
            ->getQuery()
            ->getResult()
            ;
    }

    public function latestWithAuthor(QueryBuilder $qb = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($qb)
            ->orderBy('a.publishedAt', 'DESC')
            ->innerJoin('a.author', 'u')
            ->addSelect('u');
    }

    public function published(QueryBuilder $qb = null): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder($qb)->andWhere('a.publishedAt IS NOT NULL');
    }

    public function getOrCreateQueryBuilder(?QueryBuilder $qb): QueryBuilder
    {
        return true === isset($qb) ? $qb : $this->createQueryBuilder('a');
    }
}
