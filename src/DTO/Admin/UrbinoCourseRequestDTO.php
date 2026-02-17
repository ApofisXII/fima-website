<?php

namespace App\DTO\Admin;

use App\Entity\UrbinoCourse;
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

        #[Assert\NotBlank(message: "Il tipo di orario è obbligatorio.")]
        #[Assert\Choice(choices: UrbinoCourse::SCHEDULE_TYPES, message: "Il tipo di orario selezionato non è valido.")]
        public ?string $scheduleType = null,

        #[Assert\NotBlank(message: "La data di inizio è obbligatoria.")]
        public ?string $dateStart = null,

        #[Assert\NotBlank(message: "La data di fine è obbligatoria.")]
        public ?string $dateEnd = null,

        public ?float $priceEuros = null,
    ) {}
}
