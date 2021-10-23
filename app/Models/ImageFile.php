<?php

namespace App\Models;

use App\Services\ImgixService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Webpatser\Uuid\Uuid;

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

    protected $appends = [
        'thumbnail_url',
        'preview_url',
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

    public function getThumbnailUrlAttribute($value): string
    {
        return ImgixService::getThumbnailUrl($this);
    }

    public function getPreviewUrlAttribute($value): string
    {
        return ImgixService::getPreviewUrl($this);
    }
}
