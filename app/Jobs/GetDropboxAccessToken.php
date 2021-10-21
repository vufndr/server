<?php

namespace App\Jobs;

use App\Models\DropboxAccessToken;
use App\Models\User;
use App\Services\DropboxService;
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

    protected $user;
    protected $code;

    public function __construct(User $user, $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    public function handle()
    {
        $dropbox = app(DropboxService::class);
        $tokenRepo = app(DropboxAccessToken::class);

        $tokenRepo->create([
            'user_id' => $this->user->id,
            'access_token' => $dropbox->getAccessToken($this->code)->jsonSerialize(),
        ]);

        Log::info($dropbox->getAccountInfo($this->user->id));
    }
}
