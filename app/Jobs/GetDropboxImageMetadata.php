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
        $file = app(DropboxService::class)->download($this->user->id, $this->image->path);
        Log::info($file);
    }
}
