<?php

namespace App;

use App\BaseModel;
use App\ExchangeSpaceMember;
use App\Support\ExchangeSpaceDealType;
use Illuminate\Database\Eloquent\Model;
use App\Support\Notification\HasNotifications;
use App\Support\Notification\NotificationType;

class ExchangeSpaceNotification extends BaseModel
{
    use HasNotifications;

    protected $fillable = [
        'user_id',
        'exchange_space_id',
        'type',
        'exchange_space_title',
        'exchange_space_deal',
        'exchange_space_status',
        'buyer_name',
        'requested_member_name',
        'removed_member_name',
        'business_name',
        'deal_label',
        'rejected_reason',
        'rejected_explanation',
        'exit_message',
        'close_message',
        'removed_member_id',
        'requested_member_id'
    ];

    protected $casts = [
        'user_id' => 'number',
        'exchange_space_id' => 'number',
        'type' => 'number',
        'exchange_space_title' => 'string',
        'exchange_space_deal' => 'number',
        'exchange_space_status' => 'number',
        'buyer_name' => 'string',
        'read' => 'boolean',
    ];

    protected $appends = [
        'slug',
        'url',
        'message_body',
        'message_icon',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'removed_member',
        'requested_member',
        'listing',
    ];

    public function getExchangeSpaceTitleAttribute($value)
    {
        if (!$this->exchangeSpace) {
            return $value;
        }

        $title = optional($this->exchangeSpace->allMembers->where('user_id', $this->user_id)->first())->custom_title;

        return $title ? $title : $value;
    }

    public function getSellerNameAttribute($value)
    {
        $space = optional($this->exchangeSpace);
        $name = optional($space->seller_user)->name;

        return $name ? $name : $value;
    }

    public function getBuyerNameAttribute($value)
    {
        $space = optional($this->exchangeSpace);
        $name = optional($space->buyer_user)->name;

        return $name ? $name : $value;
    }

    public function getMemberAttribute()
    {
        return $this->exchangeSpace->allMembers()->withTrashed()
        ->where('user_id', $this->user_id)->take(1)->first();
    }

    public function exchangeSpace()
    {
        return $this->belongsTo('App\ExchangeSpace', 'exchange_space_id')->withTrashed();
    }

    public function listing()
    {
        return $this->exchangeSpace->listing()->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getDealLabelAttribute()
    {
        return ExchangeSpaceDealType::getLabel($this->exchange_space_deal);
    }

    public function getStatusLabelAttribute()
    {
        return ExchangeSpaceStatusType::getLabel($this->exchange_space_status);
    }

    public function getRemovedMemberAttribute()
    {
        return ExchangeSpaceMember::where('id', $this->removed_member_id)->withTrashed()->first();
    }

    public function getRequestedMemberAttribute()
    {
        return ExchangeSpaceMember::where('id', $this->requested_member_id)->withTrashed()->first();
    }

    public function setRead()
    {
        $this->read = true;
        $this->save();

        return $this;
    }
}
