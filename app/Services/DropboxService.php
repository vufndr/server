<?php

namespace App\Services;

use App\Models\DropboxAccessToken;
use Spatie\Dropbox\Client;
use Stevenmaguire\OAuth2\Client\Provider\Dropbox;

class DropboxService
{
    public function getAuthorizationUrl($user_id)
    {
        $dropbox = new Dropbox([
            'clientId' => config('services.dropbox.key'),
            'clientSecret' => config('services.dropbox.secret'),
            'redirectUri' => config('services.dropbox.redirect_uri'),
        ]);

        $authorizationUrl = $dropbox->getAuthorizationUrl([
            'token_access_type' => 'offline',
        ]);

        session([__CLASS__ => $dropbox->getState()]);

        return $authorizationUrl;
    }

    public function getAccessToken($user_id, $code, $state)
    {
        if ($state === session(__CLASS__)) {
            $dropboxCode = app(DropboxCode::class)->make();
            $dropboxCode->user_id = $user_id;
            $dropboxCode->code = $code;
            $dropboxCode->job_status = 'created';
            $dropboxCode->save();    
        }
    }

    public function exchangeCodeForAccessToken($user_id, $code)
    {
        $dropbox = new Dropbox([
            'clientId' => config('services.dropbox.key'),
            'clientSecret' => config('services.dropbox.secret'),
            'redirectUri' => config('services.dropbox.redirect_uri'),
        ]);

        $accessToken = $dropbox->getAccessToken('authorization_code', [
            'code' => $code,
        ]);

        $dropboxAccessToken = app(DropboxAccessToken::class)->make();
        $dropboxAccessToken->user_id = $user_id;
        $dropboxAccessToken->access_token = $accessToken->jsonSerialize();
        $dropboxAccessToken->save();
    }

    public function getClient($user_id)
    {
        return new Client(new AutoRefreshingDropboxTokenService($user_id));
    }
}
