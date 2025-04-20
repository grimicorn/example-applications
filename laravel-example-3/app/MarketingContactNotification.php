<?php

namespace App;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class MarketingContactNotification extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'preferred_contact_method',
        'message',
    ];
}
