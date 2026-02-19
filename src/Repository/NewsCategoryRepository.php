<?php

namespace App\Repository;

use App\Entity\NewsCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NewsCategory>
 */
class NewsCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsCategory::class);
    }

    public function save(NewsCategory $entity): NewsCategory
    {
        $entity->setUpdatedAt(new \DateTime());
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }
}
