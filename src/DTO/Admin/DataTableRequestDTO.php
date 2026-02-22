<?php

namespace App\DTO\Admin;

class DataTableRequestDTO
{

    public function __construct(

        public ?int $draw,
        public ?array $search,
        public ?int $length,
        public ?int $start,
        public ?array $order = null,

    ) {}

}
