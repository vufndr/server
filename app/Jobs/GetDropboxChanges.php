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
use Illuminate\Support\Facades\Log;
use Throwable;

class GetDropboxChanges implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        $dropbox = app(DropboxService::class);

        $account = app(DropboxAccount::class)
            ->whereUserId($this->user->id)
            ->first();

        $changes = $dropbox->getChanges($this->user->id, $account->cursor);        

        $changes->entries()->foreach(function ($change) {
            Log::info($change->type());
        });

        $account->update([
            'cursor' => $changes->cursor(),
        ]);

        if ($changes->hasMore()) {
            GetDropboxChanges::dispatch($this->user);
        }
    }
}
