<?php

namespace App\DTO\Admin;

use App\Entity\UrbinoEvent;
use Symfony\Component\Validator\Constraints as Assert;

class UrbinoEventRequestDTO
{
    public function __construct(
        public int $eventId = 0,

        #[Assert\NotBlank]
        public int $urbinoEditionId = 0,

        #[Assert\NotBlank]
        public string $eventDatetime = '',

        #[Assert\NotBlank]
        public string $title = '',

        #[Assert\NotBlank]
        public string $subtitleIt = '',

        public ?string $subtitleEn = null,
        public ?string $locationShort = null,
        public ?string $locationLong = null,
        public ?string $ticketLink = null,
        public ?string $descriptionIt = null,
        public ?string $descriptionEn = null,
        public ?bool $isPublic = false,
        public ?bool $isDeleted = false,

        #[Assert\NotBlank]
        #[Assert\Choice(choices: UrbinoEvent::CATEGORIES)]
        public string $category = '',
    ) {}
}
