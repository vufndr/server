<?php

namespace App\Http\Controllers;

use App\Models\DropboxCode;
use Stevenmaguire\OAuth2\Client\Provider\Dropbox;

class OAuthDropboxController extends Controller
{
    public function show()
    {
        $authorizationUrl = app(DropboxService::class)
            ->getAuthorizationUrl(auth()->user()->id);

        return response()->json([
            'authorization_url' => $authorizationUrl,
        ]);
    }

    public function store()
    {
        $this->validate(request(), [
            'code' => 'required|string',
            'state' => 'required|string|',
        ]);

        app(DropboxService::class)
            ->getAccessToken(auth()->user()->id, request('code'), request('state'));
    }
}
