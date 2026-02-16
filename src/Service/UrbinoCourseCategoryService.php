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

        // Calculate ordering
        $maxOrdering = $this->urbinoCourseCategoryRepository->createQueryBuilder('c')
            ->select('MAX(c.ordering)')
            ->getQuery()
            ->getSingleScalarResult();

        $category = (new UrbinoCourseCategory())
            ->setNameIt($payload->nameIt)
            ->setNameEn($payload->nameEn)
            ->setIsDeleted(false)
            ->setOrdering(($maxOrdering ?? 0) + 1)
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

    public function updateOrdering(array $diffCategories): void
    {
        $em = $this->urbinoCourseCategoryRepository->getEntityManager();

        foreach ($diffCategories as $diff) {
            $category = $this->urbinoCourseCategoryRepository->find($diff['id']);
            $category->setOrdering($diff['new_position'] + 1);
            $category->setUpdatedAt(new \DateTime());
        }

        $em->flush();

        $allCategories = $this->urbinoCourseCategoryRepository->findBy([], ['ordering' => 'ASC']);

        $position = 1;
        foreach ($allCategories as $category) {
            $category->setOrdering($position);
            $position++;
        }

        $em->flush();
    }
}
