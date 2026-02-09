<?php

namespace App\Repository;

use App\Entity\UrbinoCourse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UrbinoCourse>
 */
class UrbinoCourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UrbinoCourse::class);
    }

    public function save(UrbinoCourse $entity): UrbinoCourse
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }
}
