<?php

namespace App\Service;

use App\DTO\Admin\UrbinoCourseCategoryRequestDTO;
use App\Entity\UrbinoCourseCategory;
use App\Repository\UrbinoCourseCategoryRepository;

final readonly class UrbinoCourseCategoryService
{
    public function __construct(
        private UrbinoCourseCategoryRepository $urbinoCourseCategoryRepository,
    ) {}

    public function create(UrbinoCourseCategoryRequestDTO $payload): UrbinoCourseCategory
    {
        // Controlla se esiste già una categoria con lo stesso nome italiano
        $existingCategory = $this->urbinoCourseCategoryRepository->createQueryBuilder('c')
            ->andWhere('c.name_it = :nameIt')
            ->andWhere('c.is_deleted = false')
            ->setParameter('nameIt', $payload->nameIt)
            ->getQuery()
            ->getOneOrNullResult();

        if ($existingCategory) {
            throw new \Exception('Esiste già una categoria con questo nome');
        }

        $category = (new UrbinoCourseCategory())
            ->setNameIt($payload->nameIt)
            ->setNameEn($payload->nameEn)
            ->setIsDeleted(false)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        return $this->urbinoCourseCategoryRepository->save($category);
    }

    public function update(UrbinoCourseCategory $category, UrbinoCourseCategoryRequestDTO $payload): UrbinoCourseCategory
    {
        $category
            ->setNameIt($payload->nameIt)
            ->setNameEn($payload->nameEn)
            ->setUpdatedAt(new \DateTime());

        return $this->urbinoCourseCategoryRepository->save($category);
    }

    public function delete(UrbinoCourseCategory $category): UrbinoCourseCategory
    {
        $category->setIsDeleted(true);
        $category->setUpdatedAt(new \DateTime());

        return $this->urbinoCourseCategoryRepository->save($category);
    }
}
