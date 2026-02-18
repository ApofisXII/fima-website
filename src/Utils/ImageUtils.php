<?php

namespace App\Utils;

use claviska\SimpleImage;
use Exception;

readonly class ImageUtils
{
    /**
     * @throws Exception
     */
    public function compressImage(string $image_path): void
    {
        (new SimpleImage())
            ->fromFile($image_path)
            ->resize(1024)
            ->autoOrient()
            ->toFile($image_path, 'image/webp', 80);
    }
}
