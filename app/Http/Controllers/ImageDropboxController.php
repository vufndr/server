<?php

namespace App\Http\Controllers;

use App\Models\ImageFile;
use App\Services\DropboxService;

class ImageDropboxController extends Controller
{
    public function show(ImageFile $image)
    {
        return response()->streamDownload(function () {
            echo stream_get_contents(app(DropboxService::class)->download($image->user_id, $image->path));
        }, basename($image->path));
    }
}
