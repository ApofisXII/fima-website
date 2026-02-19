<?php

namespace App\DataFixtures;

use App\DTO\Admin\UrbinoCourseCategoryRequestDTO;
use App\Service\UrbinoCourseCategoryService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UrbinoCourseCategoryFixtures extends Fixture
{
    public const CATEGORY_ARCHI_REF         = 'category-archi';
    public const CATEGORY_FIATI_REF         = 'category-fiati';
    public const CATEGORY_CANTO_REF         = 'category-canto';
    public const CATEGORY_TASTIERE_REF      = 'category-tastiere';
    public const CATEGORY_MUSICA_CAMERA_REF = 'category-musica-da-camera';

    public function __construct(
        private readonly UrbinoCourseCategoryService $urbinoCourseCategoryService,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $categories = [
            self::CATEGORY_ARCHI_REF => new UrbinoCourseCategoryRequestDTO(
                nameIt: 'Archi',
                nameEn: 'Strings',
            ),
            self::CATEGORY_FIATI_REF => new UrbinoCourseCategoryRequestDTO(
                nameIt: 'Fiati',
                nameEn: 'Wind Instruments',
            ),
            self::CATEGORY_CANTO_REF => new UrbinoCourseCategoryRequestDTO(
                nameIt: 'Canto',
                nameEn: 'Singing',
            ),
            self::CATEGORY_TASTIERE_REF => new UrbinoCourseCategoryRequestDTO(
                nameIt: 'Tastiere',
                nameEn: 'Keyboard Instruments',
            ),
            self::CATEGORY_MUSICA_CAMERA_REF => new UrbinoCourseCategoryRequestDTO(
                nameIt: 'Musica da camera',
                nameEn: 'Chamber Music',
            ),
        ];

        foreach ($categories as $ref => $dto) {
            $category = $this->urbinoCourseCategoryService->create($dto);
            $this->addReference($ref, $category);
        }
    }
}
