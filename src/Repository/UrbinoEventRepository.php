<?php

namespace App\Repository;

use App\Entity\UrbinoEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UrbinoEvent>
 */
class UrbinoEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UrbinoEvent::class);
    }

    public function save(UrbinoEvent $entity): UrbinoEvent
    {
        $entity->setUpdatedAt(new \DateTime());
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }
}
