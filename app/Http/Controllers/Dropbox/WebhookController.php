<?php

namespace App\Http\Controllers\Dropbox;

use App\Http\Controllers\Controller;
use App\Jobs\Dropbox\GetChanges;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function show()
    {
        return request('challenge');
    }

    public function store()
    {
        collect(request('list_folder.accounts'))
            ->each(function ($account_id) {
                $user = User::whereHasDropboxAccountId($account_id)->first();
                GetChanges::dispatch($user);
            });
    }
}
