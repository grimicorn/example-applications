<?php

namespace App;

use App\BaseModel;
use App\ConversationNotification;
use Illuminate\Database\Eloquent\Model;
use App\Support\ExchangeSpaceStatusType;
use App\Support\ConversationCategoryType;
use App\Support\ExchangeSpace\MemberRole;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\Notification\HasNotifications;
use App\Support\Notification\NotificationType;

class Conversation extends BaseModel
{
    use HasNotifications, SoftDeletes;

    protected $fillable = [
      'resolved',
      'title',
      'category',
      'is_inquiry',
    ];

    protected $casts = [
        'resolved' => 'boolean',
        'title' => 'string',
        'category' => 'integer',
        'exchange_space_id' => 'integer',
        'is_inquiry' => 'boolean',
    ];

    protected $appends = [
        'show_url',
        'category_label',
        'latest_message',
        'notification_count',
        'has_notifications',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['space'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['user'];

    /**
     * Get the exchange space that owns the conversation.
     */
    public function space()
    {
        return $this->belongsTo('App\ExchangeSpace', 'exchange_space_id')->withTrashed();
    }

    public function user()
    {
        return $this->space->user();
    }

    /**
     * Get the conversations messages.
     */
    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    /**
     * Scopes the query to only include the business inquiry.
     *
     * @param Builder $query
     * @return void
     */
    public function scopeOfInquiry($query)
    {
        return $query->where('category', '=', ConversationCategoryType::BUYER_INQUIRY)->take(1);
    }

    /**
     * Gets the buyer attribute.
     *
     * @return void
     */
    public function getBuyerAttribute()
    {
        return $this->space->members
               ->where('role', MemberRole::BUYER)
               ->first();
    }

    /**
     * Gets the seller attribute.
     *
     * @return void
     */
    public function getSellerAttribute()
    {
        return $this->space->members
               ->where('role', MemberRole::SELLER)
               ->first();
    }

    /**
     * Gets the category label attribute.
     *
     * @return string
     */
    public function getCategoryLabelAttribute()
    {
        return ConversationCategoryType::getLabel($this->category);
    }

    /**
     * Set the category attribute.
     *
     * @param mixed $value
     * @return void
     */
    public function setCategoryAttribute($value)
    {
        // If the exchange space is a buyer inquire then the conversation will be too.
        if ($this->is_buyer_inquiry) {
            $this->attributes['category'] = ConversationCategoryType::BUYER_INQUIRY;
        } else {
            $this->attributes['category'] = $value;
        }
    }

    /**
     * Gets If the conversation is a business inquiry.
     *
     * @return boolean
     */
    public function getIsBuyerInquiryAttribute()
    {
        return !optional($this->space)->accepted();
    }

    /**
     * Gets If the conversation is a business inquiry category.
     *
     * @return boolean
     */
    public function getIsBuyerInquiryCategoryAttribute()
    {
        return $this->category === ConversationCategoryType::BUYER_INQUIRY;
    }

    /**
     * Get the exchange space's show URL.
     *
     * @param  string  $value
     * @return string
     */
    public function getShowUrlAttribute()
    {
        if ($this->is_buyer_inquiry) {
            return route('business-inquiry.show', ['id' => $this->space->id]);
        }

        return $this->space_url;
    }

    /**
     * Get the conversation exchange space show URL attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getSpaceUrlAttribute()
    {
        return route('exchange-spaces.conversations.show', [
            'id' => $this->space->id,
            'c_id' => $this->id,
        ]);
    }

    /**
     * Scopes a query to conversations of the current user.
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeOfCurrentUser($query)
    {
        return $query->where('user_id', '=', Auth::id());
    }

    /**
     * Gets the conversations latest message.
     *
     * @return App\Message
     */
    public function getLatestMessageAttribute()
    {
        return $this->messages()->latest()->first();
    }

    /**
     * Gets the conversations notifictions count.
     *
     * @return void
     */
    public function getNotificationCountAttribute()
    {
        return $this->getUnreadNotifications()->count();
    }

    protected function getUnreadNotifications()
    {
        return $this->getUserNotificationsForConversation($this->id)
        ->where('read', false);
    }

    public function clearNotifications()
    {
        ConversationNotification::where('conversation_id', $this->id)
        ->get()->map->delete();

        return $this;
    }

    public function messageCountSinceLastDigest()
    {
        return $this->messages->where('updated_at', '>=', $this->user->dueDiligenceDigestLastSent())
        ->where('updated_at', '<=', now())->count();
    }

    protected function getDigestCountAttribute()
    {
        return $this->messageCountSinceLastDigest();
    }

    public function getHasNotificationsAttribute()
    {
        $count = $this->notification_count;
        if ($this->space->is_inquiry) {
            $count = $count + $this->getUnreadInquiryNotifications()->count();
        }

        return $count > 0;
    }

    public function getUnreadInquiryNotifications()
    {
        return $this->space->getCurrentUserUnreadNotifications()
        ->where('type', NotificationType::NEW_BUYER_INQUIRY);
    }

    public function readBuyerInquiryNotifications()
    {
        $this->getUnreadInquiryNotifications()->each->setRead();
        $this->getUnreadNotifications()->each->setRead();

        return $this;
    }

    public function readExchangeSpaceNotifications()
    {
        $this->getUnreadNotifications()
        ->whereIn('type', [
            NotificationType::MESSAGE,
            NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION,
            NotificationType::NEW_BUYER_INQUIRY,
        ])->each->setRead();
    }

    public function isReadonly()
    {
        return !$this->space->is_inquiry and $this->is_inquiry;
    }

    public function dispatchCreatedNotification()
    {
        if (optional($this->space)->status !== ExchangeSpaceStatusType::INQUIRY) {
            // Set the  notification
            $this->dispatchConversationNotifications(
                $this,
                NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION
            );
        }
    }
}
