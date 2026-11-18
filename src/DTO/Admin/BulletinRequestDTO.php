<?php

namespace App\DTO\Admin;

use Symfony\Component\Validator\Constraints as Assert;

class BulletinRequestDTO
{
    public function __construct(

        public ?string $bulletinId,

        #[Assert\NotBlank(message: "Il numero è obbligatorio.")]
        public ?int $number,

        #[Assert\NotBlank(message: "L'anno è obbligatorio.")]
        public ?int $year,

        #[Assert\Length(max: 255, maxMessage: "Il campo \"A cura di\" può contenere al massimo {{ limit }} caratteri.")]
        public ?string $editor = null,

        #[Assert\Length(max: 500, maxMessage: "Il campo \"Autori\" può contenere al massimo {{ limit }} caratteri.")]
        public ?string $authors = null,

        public ?bool $isPublic = false,

    ) {}
}
