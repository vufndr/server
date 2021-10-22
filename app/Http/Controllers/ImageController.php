<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ImageFile;
use App\Services\Dropbox\DropboxService;
use Exception;

class ImageController extends Controller
{
    public function show(ImageFile $image)
    {
        if ($image->provider === 'dropbox') {
            return response()->streamDownload(function () use ($image) {
                echo stream_get_contents(app(DropboxService::class)->download($image->user_id, $image->path));
            }, basename($image->path));
        } else {
            throw new Exception('Unknown image provider.');
        }
    }
}
