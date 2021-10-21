<?php

namespace App\Http\Controllers;

use App\Services\DropboxService;
use App\Jobs\GetDropboxAccessToken;

class OAuthDropboxController extends Controller
{
    public function show(DropboxService $dropbox)
    {
        return response()->json([
            'authorization_url' => $dropbox->getAuthorizationUrl(),
        ]);
    }

    public function store()
    {
        $this->validate(request(), [
            'code' => 'required|string',
        ]);

        GetDropboxAccessToken::dispatch(auth()->user(), request('code'));
    }
}
