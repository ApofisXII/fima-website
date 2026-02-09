<?php

namespace App\DTO\Admin;

use Symfony\Component\Validator\Constraints as Assert;

class NewsRequestDTO
{

    public function __construct(

        public ?string $newsId,

        #[Assert\Length(min: 5, minMessage: "Il titolo in italiano deve contenere almeno {{ limit }} caratteri.")]
        public ?string $titleIt,

        #[Assert\Length(min: 5, minMessage: "Il titolo in inglese deve contenere almeno {{ limit }} caratteri.")]
        public ?string $titleEn,

        #[Assert\Length(min: 10, minMessage: "Il corpo in italiano deve essere lungo almeno {{ limit }} caratteri.")]
        public ?string $bodyIt,

        #[Assert\Length(min: 10, minMessage: "Il corpo in inglese deve essere lungo almeno {{ limit }} caratteri.")]
        public ?string $bodyEn,

        public ?bool $isPublic = false,

        public ?bool $isEvent = false,

        public ?string $eventDatetime = null,

    ) {}

}
