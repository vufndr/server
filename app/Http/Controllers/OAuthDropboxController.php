<?php

namespace App\Http\Controllers;

use App\Jobs\TestJob;

class OAuthDropboxController extends Controller
{
    public function show()
    {
        $dropbox = new Dropbox([
            'clientId' => 'uaqnqcv23atv4qm',
            'clientSecret' => 'hjynlrrnr649chv',
            'redirectUri' => 'https://vufndr.com/oauth/dropbox',
        ]);

        session('dropbox', $dropbox->getState());

        return response()->json([
            'authorization_url' => $dopbox->getAuthorizationUrl(),
        ]);
    }

    public function store()
    {
        $this->validate(request(), [
            'code' => 'required|string',
            'state' => 'required|string|in:' .  session('dropbox'),
        ]);

        $dropboxCode = app(DropboxCode::class)->make();
        $dropboxCode->user_id = auth()->user()->id;
        $dropboxCode->code = request('code');
        $dropboxCode->save();
    }
}
