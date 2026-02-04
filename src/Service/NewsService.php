<?php

namespace App\Service;

use App\Repository\NewsRepository;

class NewsService
{

    public function __construct(
        private NewsRepository $newsRepository,
    ) {}

}
