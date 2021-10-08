<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropboxAccessToken extends Model
{
    use HasFactory;

    protected $casts = [
        'access_token' => 'array',
    ];
}
