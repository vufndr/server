<?php

namespace App\Services;

use App\Models\ImageFile;

class ImgixService
{
    private function getThumbnailUrl(ImageFile $image)
    {
        $url = config('services.imgix.url') . '/' . $image->uuid . '/?w=256&h=256';
        $signature = md5(config('services.imgix.token') + $url);
        $url .= '&s=' . $signature;
        return $url;
    }

    private function getPreviewUrl(ImageFile $image)
    {
        $url = config('services.imgix.url') . '/' . $image->uuid . '/?w=1024&h=1024';
        $signature = md5(config('services.imgix.token') + $url);
        $url .= '&s=' . $signature;
        return $url;
    }
}
