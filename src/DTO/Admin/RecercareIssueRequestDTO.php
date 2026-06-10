<?php

namespace App\DTO\Admin;

use Symfony\Component\Validator\Constraints as Assert;

class RecercareIssueRequestDTO
{
    public function __construct(

        public ?string $issueId,

        #[Assert\NotBlank(message: "Il numero del volume è obbligatorio.")]
        public ?int $volume,

        #[Assert\NotBlank(message: "L'anno è obbligatorio.")]
        public ?int $year,

        public ?string $isbn = null,

        public ?bool $isPublic = false,

    ) {}
}
