<?php

namespace App;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class SystemMessage extends BaseModel
{
    protected $fillable = [
        'message',
        'active',
        'urgency'
    ];

    protected $casts = [
        'message' => 'string',
        'active' => 'boolean',
        'urgency' => 'string'
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
