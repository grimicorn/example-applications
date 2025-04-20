<?php

namespace App;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class RejectionReason extends BaseModel
{
    protected $fillable = [
        'reason',
        'explanation',
        'exchange_space_id',
    ];

    public function space()
    {
        return $this->belongsTo('App\ExchangeSpace');
    }
}
