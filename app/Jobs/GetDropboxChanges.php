<?php

namespace App\Jobs;

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
        $changes = app(DropboxService::class)->getChanges($this->user->id, $this->user->dropboxAccount->cursor);

        $changes->entries()->each(function ($change) {
            switch ($change->type()) {
                case 'file':
                    $this->user->images()
                        ->dropbox()
                        ->where('path', 'like', $change->path() . '%')
                        ->delete();

                    $this->user->images()
                        ->create([
                            'provider' => 'dropbox',
                            'path' => $change->path(),
                        ]);

                    break;
                case 'folder':
                    // noop
                    break;
                case 'deleted':
                    $this->user->images()
                        ->dropbox()
                        ->where('path', 'like', $change->path() . '%')
                        ->delete();

                    break;
                default:
                    // noop
                    break;
            }
        });

        $this->user->dropboxAccount->update([
            'cursor' => $changes->cursor(),
        ]);

        if ($changes->hasMore()) {
            GetDropboxChanges::dispatch($this->user);
        }
    }
}
