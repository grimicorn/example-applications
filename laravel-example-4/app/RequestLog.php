<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'count' => 'integer',
    ];

    /**
     * Guarded columns
     */
    protected $guarded = [];

    /**
     * Get the user that owns the phone.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
