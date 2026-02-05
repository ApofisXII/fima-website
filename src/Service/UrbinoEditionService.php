<?php

namespace App\Service;

use App\Repository\UrbinoEditionRepository;

final readonly class UrbinoEditionService
{

    public function __construct(
        private UrbinoEditionRepository $urbinoEditionRepository,
    ) {}



}
