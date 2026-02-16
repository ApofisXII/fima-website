<?php

namespace App\Repository;

use App\Entity\UrbinoCourseCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UrbinoCourseCategory>
 */
class UrbinoCourseCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UrbinoCourseCategory::class);
    }

    public function save(UrbinoCourseCategory $entity): UrbinoCourseCategory
    {
        $entity->setUpdatedAt(new \DateTime());
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }
}
