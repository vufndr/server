<?php

namespace App\Http\Controllers;

use App\Jobs\GetDropboxChanges;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class WebhookDropboxController extends Controller
{
    public function show()
    {
        return request('challenge');
    }

    public function store()
    {
        collect(request('list_folder.accounts'))
            ->each(function ($account_id) {
                $user = User::whereDropboxAccountId($account_id)->first();
                GetDropboxChanges::dispatch($user);
            });
    }
}
