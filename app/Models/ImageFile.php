<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class ImageFile extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'uuid',
        'user_id',
        'provider',
        'path',
        'description',
        'resolution',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function toSearchableArray()
    {
        return [
            'user_id' => $this->user_id,
            'description' => $this->description,
            'resolution' => $this->resolution,
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
