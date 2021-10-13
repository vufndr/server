<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackedJob extends Model
{
    protected $fillable = [
        'class',
        'arguments',
        'status',
    ];

    public function retry()
    {
        return $this->class::redispatchTrackedJob($this);
    }
}
