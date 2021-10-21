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
        Log::info(request()->all());
    }
}
