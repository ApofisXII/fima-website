<?php

namespace App\Repository;

use App\Entity\UrbinoEdition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UrbinoEdition>
 */
class UrbinoEditionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UrbinoEdition::class);
    }

    public function save(UrbinoEdition $entity): UrbinoEdition
    {
        $entity->setUpdatedAt(new \DateTime());
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    public function findLatest(): ?UrbinoEdition
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.is_deleted = false')
            ->addOrderBy('u.date_start', 'desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findCurrentEdition(): ?UrbinoEdition
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.is_deleted = false')
            ->andWhere('u.is_public_visible = true')
            ->addOrderBy('u.date_start', 'desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
