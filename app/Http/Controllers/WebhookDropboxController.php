<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

class WebhookDropboxController extends Controller
{
    public function show()
    {
        return request('challenge');
    }

    public function store()
    {
        $accounts = collect(request('list_folder.accounts'));
        Log::info($accounts->toArray());
    }
}
