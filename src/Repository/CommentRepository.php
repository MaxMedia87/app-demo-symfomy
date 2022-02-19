<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @param string|null $query
     * @param bool $showWithDeletes
     *
     * @return Comment[]
     */
    public function findAllWithSearch(?string $query, bool $showWithDeletes = false): array
    {
        $qb = $this->createQueryBuilder('c');

        if (null !== $query) {
            $qb
                ->andWhere('c.content LIKE :query OR c.authorName LIKE :query OR a.title LIKE :query')
                ->setParameter('query', "%$query%");
        }

        if (true === $showWithDeletes) {
            $this->getEntityManager()->getFilters()->disable('softdeleteable');
        }

        return $qb
                ->innerJoin('c.article', 'a')
                ->orderBy('c.createdAt', 'DESC')
                ->getQuery()
                ->getResult();
    }
}
