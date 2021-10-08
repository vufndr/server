<?php

namespace App\Services;

use App\Models\DropboxAccessToken;
use League\OAuth2\Client\Token\AccessToken;
use Spatie\Dropbox\TokenProvider;
use Stevenmaguire\OAuth2\Client\Provider\Dropbox;

class AutoRefreshingDropboxTokenService implements TokenProvider
{
    protected $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getToken()
    {
        $dropbox = new Dropbox([
            'clientId' => config('services.dropbox.key'),
            'clientSecret' => config('services.dropbox.secret'),
            'redirectUri' => config('services.dropbox.redirect_uri'),
        ]);

        $dropboxAccessToken = app(DropboxAccessToken::class)
            ->whereUserId($this->user_id)
            ->first();

        $accessToken = new AccessToken($dropboxAccessToken->access_token);

        if ($accessToken->hasExpired()) {
            $accessToken = $dropbox->getAccessToken('refresh_token', [
                'refresh_token' => $accessToken->getRefreshToken()
            ]);
            $dropboxAccessToken->access_token = $accessToken->jsonSerialize();
            $dropboxAccessToken->save();
        }
    }
}
