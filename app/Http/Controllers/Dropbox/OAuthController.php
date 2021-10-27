<?php

namespace App\Http\Controllers\Dropbox;

use App\Http\Controllers\Controller;
use App\Jobs\Dropbox\GetAccessToken;
use App\Services\Dropbox\DropboxService;

class OAuthController extends Controller
{
    public function show(DropboxService $dropbox)
    {
        return response()->json([
            'authorization_url' => auth()->user()->dropboxAccount()->exists() ? '' : $dropbox->getAuthorizationUrl(),
        ]);
    }

    public function store()
    {
        $this->validate(request(), [
            'code' => 'required|string',
        ]);

        GetAccessToken::dispatch(auth()->user(), request('code'));
    }
}
