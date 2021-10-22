<?php

namespace App\Services\Dropbox;

use App\Models\User;
use Spatie\Dropbox\TokenProvider;

class AutoRefreshingTokenService implements TokenProvider
{
    protected $user;

    public function __construct($user_id)
    {
        $this->user = User::find($user_id);
    }

    public function getToken(): string
    {
        if ($this->user->dropboxAccount->access_token->hasExpired()) {
            $this->user->dropboxAccount->access_token = app(DropboxService::class)->getAccessToken($this->user->dropboxAccount->access_token);
            $this->user->dropboxAccount->save();
        }

        return $this->user->dropboxAccount->access_token->getToken();
    }
}
