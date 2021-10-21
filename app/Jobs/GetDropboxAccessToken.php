<?php

namespace App\Jobs;

use App\Models\DropboxAccount;
use App\Models\User;
use App\Services\DropboxService;
use Illuminate\Bus\Queueable;
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
        app(DropboxAccount::class)->create([
            'user_id' => $this->user->id,
            'access_token' => app(DropboxService::class)->getAccessToken($this->code),
        ]);

        GetDropboxAccountId::dispatch($this->user);
    }
}
