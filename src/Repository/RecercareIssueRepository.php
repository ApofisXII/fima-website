<?php

namespace App\Repository;

use App\Entity\RecercareIssue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RecercareIssue>
 */
class RecercareIssueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecercareIssue::class);
    }

    public function save(RecercareIssue $entity): RecercareIssue
    {
        $entity->setUpdatedAt(new \DateTime());
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }
}
