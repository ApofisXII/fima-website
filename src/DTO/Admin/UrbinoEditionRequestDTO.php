<?php

namespace App\DTO\Admin;

use Symfony\Component\Validator\Constraints as Assert;

class UrbinoEditionRequestDTO
{

    public function __construct(

        public ?string $editionId,

        #[Assert\Length(min: 3, minMessage: "Il nome dell'edizione deve contenere almeno {{ limit }} caratteri.")]
        public ?string $editionName,

        #[Assert\Positive(message: "L'anno deve essere un numero positivo.")]
        public ?int $year,

        public ?string $periodDescription,

        public ?bool $isPublicVisible = false,

        #[Assert\NotBlank(message: "Le informazioni di iscrizione in italiano sono obbligatorie.")]
        public ?string $enrollmentInfoIt,

        #[Assert\NotBlank(message: "Le informazioni di iscrizione in inglese sono obbligatorie.")]
        public ?string $enrollmentInfoEn,

        #[Assert\NotBlank(message: "Le informazioni sull'alloggio in italiano sono obbligatorie.")]
        public ?string $accommodationInfoIt,

        #[Assert\NotBlank(message: "Le informazioni sull'alloggio in inglese sono obbligatorie.")]
        public ?string $accommodationInfoEn,

    ) {}

}
