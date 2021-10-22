<?php

namespace App\Jobs;

use App\Models\ImageFile;
use App\Services\DropboxService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Image;
use Throwable;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class GetDropboxImageMetadata implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $image;

    public function __construct(ImageFile $image)
    {
        $this->image = $image;
    }

    public function handle()
    {
        $temporaryDirectory = (new TemporaryDirectory())->create();
        $path = $temporaryDirectory->path('image');
        $file = app(DropboxService::class)->download($this->image->user_id, $this->image->path);
        file_put_contents($path, $file);
        Log::info([Image::make($path)->exif(), Image::make($path)->iptc()]);
        $temporaryDirectory->delete();
    }
}
