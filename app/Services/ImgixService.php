<?php

namespace App\Services;

use App\Models\ImageFile;
use Imgix\UrlBuilder;

class ImgixService
{
    public static function getBuilder()
    {
        $builder = new UrlBuilder(config('services.imgix.url'));
        $builder->setSignKey(config('services.imgix.token'));
        return $builder;
    }

    public static function getUrl($params)
    {
        $params['expires'] = now()->addMinutes(15)->timestamp;

        return static::getBuilder()->createURL($image->uuid, $params);
    }

    public static function getThumbnailUrl(ImageFile $image)
    {
        return static::getUrl($image, [
            'w' => 256,
            'h' => 256,
        ]);
    }

    public static function getPreviewUrl(ImageFile $image)
    {
        return static::getUrl($image, [
            'w' => 1024,
            'h' => 1024,
        ]);
    }
}
