<?php

namespace App\Http\Controllers;

use App\Models\ImageFile;
use App\Services\DropboxService;

class ImageDropboxController extends Controller
{
    public function show(ImageFile $image)
    {
        return app(DropboxService::class)->download($image->user_id, $image->path);
    }
}
