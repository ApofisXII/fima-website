<?php

namespace App\DTO\Admin;

use Symfony\Component\Validator\Constraints as Assert;

class BulletinRequestDTO
{
    public function __construct(

        public ?string $bulletinId,

        #[Assert\NotBlank(message: "Il titolo è obbligatorio.")]
        public ?string $title,

        #[Assert\NotBlank(message: "L'autore è obbligatorio.")]
        public ?string $author,

        #[Assert\NotBlank(message: "La data di pubblicazione è obbligatoria.")]
        #[Assert\Date(message: "La data di pubblicazione non è valida.")]
        public ?string $publishedAt,

        public ?bool $isPublic = false,

    ) {}
}
