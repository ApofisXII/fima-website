<?php

namespace App\DTO\Website;

use App\Entity\UrbinoCourse;
use App\Entity\UrbinoCourseCategory;

readonly class CoursesInCategoryDTO
{

    public function __construct(
        public UrbinoCourseCategory $category,

        /* @var UrbinoCourse[] $courses */
        public array $courses,
    ) {}

}
