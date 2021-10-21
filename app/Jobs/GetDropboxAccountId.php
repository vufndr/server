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

class GetDropboxAccountId implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        $this->user->dropboxAccount->update([
            'account_id' => app(DropboxService::class)->getAccountId($this->user->id),
        ]);

        GetDropboxChanges::dispatch($this->user);
    }
}
