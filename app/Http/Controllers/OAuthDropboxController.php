<?php

namespace App\Http\Controllers;

use App\Models\DropboxCode;
use Stevenmaguire\OAuth2\Client\Provider\Dropbox;

class OAuthDropboxController extends Controller
{
    public function show()
    {
        $dropbox = new Dropbox([
            'clientId' => config('services.dropbox.key'),
            'clientSecret' => config('services.dropbox.secret'),
            'redirectUri' => config('services.dropbox.redirect_uri'),
        ]);

        $authorizationUrl = $dropbox->getAuthorizationUrl([
            'token_access_type' => 'offline',
        ]);

        session(['dropbox' => $dropbox->getState()]);

        return response()->json([
            'authorization_url' => $authorizationUrl,
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
        $dropboxCode->job_status = 'created';
        $dropboxCode->save();
    }
}
