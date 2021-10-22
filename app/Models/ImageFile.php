<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class ImageFile extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'user_id',
        'provider',
        'path',
        'description',
    ];

    public function toSearchableArray()
    {
        return [
            'description' => $this->description,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeDropbox($query)
    {
        return $query->whereProvider('dropbox');
    }
}
