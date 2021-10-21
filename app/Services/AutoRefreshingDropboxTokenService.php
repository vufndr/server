<?php

namespace App\Services;

use App\Models\User;
use Spatie\Dropbox\TokenProvider;

class AutoRefreshingDropboxTokenService implements TokenProvider
{
    protected $user;

    public function __construct($user_id)
    {
        $this->user = User::find($user_id);
    }

    public function getToken(): string
    {
        if ($this->user->account->access_token->hasExpired()) {
            $this->user->account->access_token = app(DropboxService::class)->getAccessToken($this->user->account->access_token);
            $this->user->account->save();
        }

        return $this->user->account->access_token->getToken();
    }
}
