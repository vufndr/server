<?php

namespace App\Services;

use App\Clients\Dropbox\DropboxClient;
use App\Clients\Dropbox\ListFolderResult;
use App\Models\DropboxAccessToken;
use League\OAuth2\Client\Token\AccessToken;
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

    private function getClient($user_id)
    {
        return new DropboxClient(new AutoRefreshingDropboxTokenService($user_id));
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
