<?php

namespace App;

use App\Message;
use Illuminate\Database\Eloquent\Model;
use App\Support\Notification\NotificationType;

class AbuseReportLink extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'notification_type',
        'message',
        'reporter_id',
        'creator_id',
        'message_id',
        'reason',
        'reason_details',
        'message_model',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'notification_type' => 'integer',
        'reporter_id' => 'integer',
        'creator_id' => 'integer',
        'message_id' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['reported_on'];

    /**
     * The creator of the reported issue
     */
    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    /**
     * The reporter of the issue
     */
    public function reporter()
    {
        return $this->belongsTo('App\User', 'reporter_id');
    }

    public function messageModel()
    {
        return $this->hasOne($this->message_model, 'id', 'message_id');
    }

    public function getNotificationTypeLabelAttribute()
    {
        return NotificationType::getLabel($this->notification_type);
    }

    public function getActionUrlAttribute()
    {
        if ($this->message_model === Message::class) {
            $model = optional($this->messageModel);
            return optional($model->conversation)->show_url;
        }
    }

    public function getActionLabelAttribute()
    {
        if ($this->message_model === Message::class) {
            return 'View the conversation';
        }
    }

    public function redirectUrl()
    {
        if ($this->message_model === Message::class) {
            $model = optional($this->messageModel);
            return optional($model->conversation)->show_url;
        }

        return auth()->check() ? route('dashboard') : route('home');
    }
}
