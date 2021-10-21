<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider',
        'path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeDropbox($query)
    {
        return $query->whereProvider('dropbox');
    }
}
