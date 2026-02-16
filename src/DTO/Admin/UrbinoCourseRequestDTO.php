<?php

namespace App\DTO\Admin;

use Symfony\Component\Validator\Constraints as Assert;

class UrbinoCourseRequestDTO
{
    public function __construct(
        public int $courseId = 0,

        #[Assert\NotBlank]
        public string $teacherFullName = '',

        #[Assert\NotBlank]
        public int $urbinoEditionId = 0,

        #[Assert\NotBlank(message: "La categoria è obbligatoria.")]
        public ?int $urbinoCategoryId = null,

        public ?string $programDescriptionIt = null,
        public ?string $programDescriptionEn = null,
        public ?string $bioDescriptionIt = null,
        public ?string $bioDescriptionEn = null,
        public ?bool $isPreselectionRequired = false,
        public ?bool $isSoldOut = false,
        public ?bool $isAfternoonCourse = false,
        public ?float $priceEuros = null,
    ) {}
}
