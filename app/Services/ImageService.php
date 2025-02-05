<?php

namespace App\Services;

use Imagick;

class ImageService
{
    /**
     * @param  string  $path
     * @return string
     */
    public static function getPreviewPath(string $path): string
    {
        $arr = explode('/', $path);

        $lastKey = (count($arr)-1);

        $arr[$lastKey] = 'preview_' . $arr[$lastKey];

        return implode('/', $arr);
    }

    /**
     * @param  string  $path
     * @return void
     * @throws \ImagickException
     */
    public static function compression(string $path): void
    {
        $previewPath = self::getPreviewPath($path);

        if (!file_exists($previewPath)){
            $image = new Imagick($path);

            $image->setImageCompressionQuality(50);

            $image->writeImage($previewPath);
        }

    }
}
