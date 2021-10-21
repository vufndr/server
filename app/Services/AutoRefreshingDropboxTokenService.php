<?php

namespace App\Services;

use App\Models\DropboxAccessToken;
use League\OAuth2\Client\Token\AccessToken;
use Spatie\Dropbox\TokenProvider;

class AutoRefreshingDropboxTokenService implements TokenProvider
{
    protected $user_id;

    protected $dropbox;
    protected $tokenRepo;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;

        $this->dropbox = app(DropboxService::class);
        $this->tokenRepo = app(DropboxAccessToken::class);
    }

    public function getToken(): string
    {
        $token = new AccessToken($this->tokenRepo->whereUserId($this->user_id)->first()->access_token);

        if ($token->hasExpired()) {
            $token = $this->dropbox->getAccessToken($token);

            $this->tokenRepo->whereUserId($this->user_id)->update([
                'access_token' => $this->dropbox->getAccessToken($token)->jsonSerialize(),
            ]);
        }

        return $token->access_token;
    }
}
