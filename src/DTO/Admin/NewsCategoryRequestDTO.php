<?php

namespace App\DTO\Admin;

use Symfony\Component\Validator\Constraints as Assert;

class NewsCategoryRequestDTO
{
    public function __construct(
        public ?int $categoryId = 0,

        #[Assert\NotBlank(message: "Il nome italiano è obbligatorio.")]
        #[Assert\Length(
            min: 2,
            max: 255,
            minMessage: "Il nome italiano deve contenere almeno {{ limit }} caratteri.",
            maxMessage: "Il nome italiano non può superare {{ limit }} caratteri."
        )]
        public ?string $nameIt = null,

        #[Assert\NotBlank(message: "Il nome inglese è obbligatorio.")]
        #[Assert\Length(
            min: 2,
            max: 255,
            minMessage: "Il nome inglese deve contenere almeno {{ limit }} caratteri.",
            maxMessage: "Il nome inglese non può superare {{ limit }} caratteri."
        )]
        public ?string $nameEn = null,
    ) {}
}
