<?php

namespace App\DTO\Admin;

use Symfony\Component\Validator\Constraints as Assert;

class UrbinoEditionRequestDTO
{

    public function __construct(

        public ?string $editionId,

        #[Assert\Length(min: 3, minMessage: "Il nome dell'edizione deve contenere almeno {{ limit }} caratteri.")]
        public ?string $editionName,

        #[Assert\NotBlank(message: "La data di inizio è obbligatoria.")]
        public ?string $dateStart,

        #[Assert\NotBlank(message: "La data di fine è obbligatoria.")]
        public ?string $dateEnd,

        public ?bool $isPublicVisible = false,

        #[Assert\NotBlank(message: "Le informazioni per i partecipanti in italiano sono obbligatorie.")]
        public ?string $enrollmentInfoIt,

        #[Assert\NotBlank(message: "Le informazioni per i partecipanti in inglese sono obbligatorie.")]
        public ?string $enrollmentInfoEn,

        public ?string $enrollmentLink = null,

    ) {}

}
