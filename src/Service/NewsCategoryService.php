<?php

namespace App\Service;

use App\DTO\Admin\NewsCategoryRequestDTO;
use App\Entity\NewsCategory;
use App\Repository\NewsCategoryRepository;

final readonly class NewsCategoryService
{
    public function __construct(
        private NewsCategoryRepository $newsCategoryRepository,
    ) {}

    public function create(NewsCategoryRequestDTO $payload): NewsCategory
    {
        $existingCategory = $this->newsCategoryRepository->createQueryBuilder('c')
            ->andWhere('c.name_it = :nameIt')
            ->andWhere('c.is_deleted = false')
            ->setParameter('nameIt', $payload->nameIt)
            ->getQuery()
            ->getOneOrNullResult();

        if ($existingCategory) {
            throw new \Exception('Esiste già una categoria con questo nome');
        }

        $category = (new NewsCategory())
            ->setNameIt($payload->nameIt)
            ->setNameEn($payload->nameEn)
            ->setIsDeleted(false)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        return $this->newsCategoryRepository->save($category);
    }

    public function update(NewsCategory $category, NewsCategoryRequestDTO $payload): NewsCategory
    {
        $category
            ->setNameIt($payload->nameIt)
            ->setNameEn($payload->nameEn)
            ->setUpdatedAt(new \DateTime());

        return $this->newsCategoryRepository->save($category);
    }

    public function delete(NewsCategory $category): NewsCategory
    {
        $category->setIsDeleted(true);
        $category->setUpdatedAt(new \DateTime());

        return $this->newsCategoryRepository->save($category);
    }
}
