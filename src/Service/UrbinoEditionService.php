<?php

namespace App\Service;

use App\Repository\UrbinoEditionRepository;

class UrbinoEditionService
{

    public function __construct(
        private UrbinoEditionRepository $urbinoEditionRepository,
    ) {}

    

}
