<?php

namespace App;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;
use App\Support\Notification\HasNotifications;

class ConversationNotification extends BaseModel
{
    use HasNotifications;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'type',
        'message_sender_name',
        'conversation_title',
        'exchange_space_title',
    ];

    protected $casts = [
        'type' => 'number',
        'sender_member_name' => 'string',
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

    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }

    public function exchangeSpace()
    {
        return $this->conversation->space()->withTrashed();
    }

    public function listing()
    {
        return $this->exchangeSpace->listing()->withTrashed();
    }

    public function getExchangeSpaceTitleAttribute($value)
    {
        $conversation = optional($this->conversation);
        $title = optional($conversation->space)->title;

        return $title ? $title : $value;
    }

    public function setRead()
    {
        $this->read = true;
        $this->save();

        return $this;
    }

    public function getMemberAttribute()
    {
        return $this->exchangeSpace->allMembers()->withTrashed()
        ->where('user_id', $this->user_id)->take(1)->first();
    }
}
