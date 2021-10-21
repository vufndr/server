<?php

namespace App\Http\Controllers;

class WebhookDropboxController extends Controller
{
    public function show()
    {
        return request('challenge');
    }

    public function store()
    {
        Log::info(request()->all());
    }
}
