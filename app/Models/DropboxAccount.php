<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use League\OAuth2\Client\Token\AccessToken;

class DropboxAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'access_token',
        'account_id',
        'cursor',
    ];

    protected $casts = [
        'access_token' => 'array',
    ];

    public function getAccessTokenAttribute($value): AccessToken
    {
        return new AccessToken($value);
    }

    public function setAccessTokenAttribute(AccessToken $value)
    {
        $this->attributes['access_token'] = $value->jsonSerialize();
    }
}
