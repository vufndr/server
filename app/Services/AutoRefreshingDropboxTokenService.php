<?php

namespace App\Services;

use App\Models\DropboxAccount;
use Spatie\Dropbox\TokenProvider;

class AutoRefreshingDropboxTokenService implements TokenProvider
{
    protected $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getToken(): string
    {
        $accountRepo = app(DropboxAccount::class);

        $account = $accountRepo
            ->whereUserId($this->user_id)
            ->first();

        if ($account->access_token->hasExpired()) {
            $account->access_token = app(DropboxService::class)->getAccessToken($account->access_token);
            $account->save();
        }

        return $account->access_token->getToken();
    }
}
