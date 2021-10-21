<?php

namespace App\Http\Controllers;

use App\Models\DropboxAccount;
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
                $user = User::whereHas('dropboxAccount', function ($query) use ($account_id) {
                    $query->whereAccountId($account_id);
                })->first();
                Log::info($user->id);
                GetDropboxChanges::dispatch($user);
            });
    }
}
