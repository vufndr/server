<?php

namespace App\Jobs;

use App\Models\DropboxCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GetDropboxAccessToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dropboxCode;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(DropboxCode $dropboxCode)
    {
        $this->dropboxCode = $dropboxCode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->dropboxCode->job_status = 'running';
        $this->dropboxCode->save();

        app(DropboxService::class)
            ->exchangeCodeForAccessToken(
                $this->dropboxCode->user_id,
                $this->dropboxCode->code
            );

        $this->dropboxCode->job_status = 'completed';
        $this->dropboxCode->save();
    }

    public function failed(?Throwable $exception)
    {
        $this->dropboxCode->job_status = 'failed';
        $this->dropboxCode->save();
    }
}
