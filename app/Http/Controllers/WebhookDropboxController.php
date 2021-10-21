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
                Log::info(DropboxAccount::whereAccountId($account_id)->first()->user_id);
            });
    }
}
