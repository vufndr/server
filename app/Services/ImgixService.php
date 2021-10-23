<?php

namespace App\Services;

use App\Models\ImageFile;

class ImgixService
{
    public static function getThumbnailUrl(ImageFile $image)
    {
        $url = config('services.imgix.url') . '/' . $image->uuid . '?w=256&h=256';
        $signature = md5(config('services.imgix.token') . $url);
        $url .= '&s=' . $signature;
        return $url;
    }

    public static function getPreviewUrl(ImageFile $image)
    {
        $url = config('services.imgix.url') . '/' . $image->uuid . '?w=1024&h=1024';
        $signature = md5(config('services.imgix.token') . $url);
        $url .= '&s=' . $signature;
        return $url;
    }
}
