<?php

namespace App\Jobs\Dropbox;

use App\Models\ImageFile;
use App\Services\Dropbox\DropboxService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Throwable;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class GetImageMetadata implements ShouldQueue
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
        $path = $temporaryDirectory->path(basename($this->image->path));
        $file = app(DropboxService::class)->download($this->image->user_id, $this->image->path);
        file_put_contents($path, $file);
        $image = Image::make($path);
        $this->image->description = Arr::get($image->iptc(), 'Caption', Arr::get($image->exif(), 'ImageDescription', null));
        $this->image->resolution = $image->width() . 'x' . $image->height();
        $this->image->save();
        $temporaryDirectory->delete();
    }
}
