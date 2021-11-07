<?php

namespace App\Services\Dropbox;

use App\Clients\Dropbox\DropboxClient;
use App\Clients\Dropbox\ListFolderResult;
use League\OAuth2\Client\Token\AccessToken;
use Stevenmaguire\OAuth2\Client\Provider\Dropbox;

class DropboxService
{
    private function getProvider($redirectUri = true)
    {
        $options = [
            'clientId' => config('services.dropbox.key'),
            'clientSecret' => config('services.dropbox.secret'),
        ];

        if ($redirectUri) {
            $options['redirectUri'] = config('services.dropbox.redirect_uri');
        }

        return new Dropbox($options);
    }

    private function getClient($user_id)
    {
        return new DropboxClient(new AutoRefreshingTokenService($user_id));
    }

    public function getAuthorizationUrl()
    {
        return $this->getProvider()->getAuthorizationUrl([
            'token_access_type' => 'offline',
        ]);
    }

    public function getAccessToken($identifier)
    {
        if ($identifier instanceof AccessToken) {
            $parameters = $identifier->getValues();

            $parameters['access_token'] = $this->getProvider(false)->getAccessToken('refresh_token', [
                'refresh_token' => $identifier->getRefreshToken()
            ])->getToken();

            return new AccessToken($parameters);
        }

        return $this->getProvider()->getAccessToken('authorization_code', [
            'code' => $identifier,
        ]);
    }

    public function getAccountId($user_id)
    {
        return $this->getClient($user_id)->getAccountInfo()['account_id'];
    }

    public function getChanges($user_id, $cursor = null)
    {
        if ($cursor) {
            return new ListFolderResult($this->getClient($user_id)->listFolderContinue($cursor));
        } else {
            return new ListFolderResult($this->getClient($user_id)->listFolder('/', true, true));
        }
    }

    public function download($user_id, $path)
    {
        return $this->getClient($user_id)->download($path);
    }
}
