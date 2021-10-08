<?php

namespace App\Jobs;

use App\Models\DropboxAccessToken;
use App\Models\DropboxCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;
use Stevenmaguire\OAuth2\Client\Provider\Dropbox;

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

        $dropbox = new Dropbox([
            'clientId' => config('services.dropbox.key'),
            'clientSecret' => config('services.dropbox.secret'),
            'redirectUri' => config('services.dropbox.redirect_uri'),
        ]);

        $accessToken = $dropbox->getAccessToken('authorization_code', [
            'code' => $this->dropboxCode->code,
        ]);

        $dropboxAccessToken = app(DropboxAccessToken::class)->make();
        $dropboxAccessToken->user_id = $this->dropboxCode->user_id;
        $dropboxAccessToken->access_token = $accessToken->jsonSerialize();
        $dropboxAccessToken->save();

        $this->dropboxCode->job_status = 'completed';
        $this->dropboxCode->save();
    }

    public function failed(?Throwable $exception)
    {
        $this->dropboxCode->job_status = 'failed';
        $this->dropboxCode->save();
    }
}
