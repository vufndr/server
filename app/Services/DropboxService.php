<?php

namespace App\Services;

use App\Models\DropboxAccessToken;
use League\OAuth2\Client\Token\AccessToken;
use Spatie\Dropbox\Client;
use Stevenmaguire\OAuth2\Client\Provider\Dropbox;

class DropboxService
{
    protected $dropbox;

    public function __construct()
    {
        $this->dropbox = new Dropbox([
            'clientId' => config('services.dropbox.key'),
            'clientSecret' => config('services.dropbox.secret'),
            'redirectUri' => config('services.dropbox.redirect_uri'),
        ]);
    }

    public function getAuthorizationUrl()
    {
       return $this->dropbox->getAuthorizationUrl([
            'token_access_type' => 'offline',
        ]);
    }

    public function getAccessToken($identifier)
    {
        if ($identifier instanceof AccessToken) {
            return $this->dropbox->getAccessToken('refresh_token', [
                'refresh_token' => $identifier->getRefreshToken()
            ]);    
        }

        return $this->dropbox->getAccessToken('authorization_code', [
            'code' => $identifier,
        ]);
    }

    public function getClient($user_id)
    {
        return new Client(new AutoRefreshingDropboxTokenService($user_id));
    }
}
