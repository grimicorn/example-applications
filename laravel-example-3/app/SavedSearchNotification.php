<?php

namespace App;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;
use App\Support\Notification\HasNotifications;

class SavedSearchNotification extends BaseModel
{
    use HasNotifications;

    protected $fillable = [
        'type',
        'rule_name',
        'listing_id',
    ];

    protected $casts = [
        'type' => 'number',
        'rule_name' => 'string',
        'read' => 'boolean',
    ];

    protected $appends = [
        'slug',
        'url',
        'message_body',
        'message_icon',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function listing()
    {
        return $this->belongsTo('App\Listing');
    }


    public function savedSearch()
    {
        return $this->belongsTo('App\SavedSearch');
    }

    public function getShowUrlAttribute()
    {
        return optional($this->listing)->show_url ?: $this->savedSearch->show_url;
    }
}
