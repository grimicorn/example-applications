<?php

namespace App;

use App\User;
use App\Message;
use App\BaseModel;
use App\RejectionReason;
use ExchangethisDealType;
use Illuminate\Support\Facades\Auth;
use App\Support\ExchangeSpaceDealType;
use Illuminate\Database\Eloquent\Model;
use App\Support\ExchangeSpaceStatusType;
use App\Support\ExchangeSpace\MemberRole;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\Notification\HasNotifications;
use App\Support\Notification\NotificationType;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class ExchangeSpace extends BaseModel implements HasMedia
{
    use HasMediaTrait;
    use SoftDeletes;
    use HasNotifications;

    protected $fillable = [
        'status',
        'deal',
        'title',
        'historical_financials_public',
    ];

    protected $casts = [
        'status' => 'integer',
        'deal' => 'integer',
        'listing_id' => 'integer',
        'user_id' => 'integer',
        'historical_financials_public' => 'boolean',
    ];

    protected $appends = [
        'buyer_user',
        'current_member',
        'deal_label',
        'show_url',
        'notification_count',
        'status_label',
        'title_edit_url',
        'buyer_user_id',
        'has_notifications',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'allMembers',
        'members',
        'buyer_user',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function listing()
    {
        return $this->belongsTo('App\Listing')->withTrashed();
    }

    public function conversations()
    {
        return $this->hasMany('App\Conversation');
    }

    public function rejectionReason()
    {
        return $this->hasOne('App\RejectionReason');
    }

    public function messages()
    {
        return $this->hasManyThrough('App\Message', 'App\Conversation');
    }

    public function historicalFinancials()
    {
        return $this->hasManyThrough('App\HistoricalFinancial', 'App\Listing');
    }

    /**
     * Get the member records associated with the exchange space.
     */
    public function members()
    {
        return $this->hasMany('App\ExchangeSpaceMember')->active()->approved();
    }

    /**
     * Get the member records associated with the exchange space.
     */
    public function allMembers()
    {
        return $this->hasMany('App\ExchangeSpaceMember');
    }

    /**
     * Get the member records associated with the exchange space.
     */
    public function membersIncludingUnapproved()
    {
        return $this->hasMany('App\ExchangeSpaceMember')->active();
    }

    /**
     * Get the member records associated with the exchange space.
     */
    public function membersPending()
    {
        return $this->hasMany('App\ExchangeSpaceMember')->pending();
    }

    public function scopeInquiries($query)
    {
        return $query->where('status', '=', ExchangeSpaceStatusType::INQUIRY);
    }

    public function scopeNotInquiries($query)
    {
        return $query->where('status', '!=', ExchangeSpaceStatusType::INQUIRY);
    }

    public function scopeOfCurrentUser($query)
    {
        return $query->where('user_id', '=', Auth::id());
    }

    /**
     * Get the exchange space's status label.
     *
     * @param  string  $value
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        return ExchangeSpaceStatusType::getLabel($this->status);
    }

    /**
     * Get the exchange space's deal label.
     *
     * @param  string  $value
     * @return string
     */
    public function getDealLabelAttribute()
    {
        return ExchangeSpaceDealType::getLabel($this->deal);
    }

    /**
     * Get the exchange space's buyer/seller.
     *
     * @param  string  $value
     * @return string
     */
    public function getBuyerSellerFormattedAttribute()
    {
        return implode('/', [
            $this->user->last_name,
            optional($this->buyer_user)->last_name,
        ]);
    }

    /**
     * Checks if the current Exchange Space is a Business Inquiry.
     *
     * @return boolean
     */
    public function getIsInquiryAttribute()
    {
        return $this->status === ExchangeSpaceStatusType::INQUIRY;
    }

    /**
     * Checks if the current Exchange Space is a rejected Business Inquiry.
     *
     * @return boolean
     */
    public function getIsRejectedAttribute()
    {
        return $this->status === ExchangeSpaceStatusType::REJECTED;
    }

    /**
     * Get the exchange space's show URL.
     *
     * @param  string  $value
     * @return string
     */
    public function getShowUrlAttribute()
    {
        return $this->showUrl();
    }

    public function showUrl($data = [])
    {
        if ($this->is_inquiry) {
            return route(
                'business-inquiry.show',
                array_merge(['id' => $this->id], $data)
            );
        }

        return route(
            'exchange-spaces.show',
            array_merge(['id' => $this->id], $data)
        );
    }

    public function acceptInquiry()
    {
        // Update the space
        $this->status = ExchangeSpaceStatusType::ACCEPTED;
        $this->deal = ExchangeSpaceDealType::PRE_NDA;
        $this->title = $this->buyer_seller_formatted; // seller/buyer last names

        $this->save();

        // Update the conversation.
        $conversation = $this->conversations->first();
        $conversation->resolved = true;
        $conversation->save();

        return $this;
    }

    public function rejectInquiry()
    {
        // Update the inquiry
        $this->status = ExchangeSpaceStatusType::REJECTED;
        $this->save();
        $reasonDefault = ($this->buyerUserRemovedByAdmin() or $this->userRemovedByAdmin()) ? 'User Left Platform' : 'Listing Removed';

        // Store the rejection reason
        RejectionReason::create([
            'reason' => request()->get('reason', $reasonDefault),
            'explanation' => request()->get('explanation', ''),
            'exchange_space_id' => $this->id,
        ]);

        return $this;
    }

    public function userRemovedByAdmin()
    {
        return (bool) optional($this->user()->withTrashed()->get()->first())->removed_by_admin;
    }

    public function buyerUserRemovedByAdmin()
    {
        return (bool) optional($this->buyerUser())->removed_by_admin;
    }

    /**
     * Get the exchange space's buyer.
     *
     * @param  string  $value
     * @return string
     */
    public function getBuyerUserAttribute()
    {
        $user = $this->buyerUser();

        if (is_null($user)) {
            return;
        }

        return $user;
    }

    public function buyerMember()
    {
        return $this->allMembers->filter(function ($member) {
            return $member->role === MemberRole::BUYER;
        })->first();
    }

    public function buyerUser()
    {
        return optional($this->buyerMember())->user;
    }

    /**
     * Get the exchange space's seller.
     *
     * @param  string  $value
     * @return string
     */
    public function getSellerUserAttribute()
    {
        $user = $this->sellerUser();

        if (is_null($user)) {
            return;
        }

        return $user;
    }

    public function sellerMember()
    {
        return $this->allMembers->filter(function ($member) {
            return $member->role === MemberRole::SELLER;
        })->first();
    }

    public function sellerUser()
    {
        return optional($this->sellerMember())->user;
    }

    /**
     * Get the exchange space's current user.
     *
     * @param  string  $value
     * @return string
     */
    public function getCurrentMemberAttribute()
    {
        return $this->allMembers()->withTrashed()->where('user_id', auth()->id())->first();
    }

    /**
     * Gets the exchange space notifictions count.
     *
     * @return void
     */
    public function getNotificationCountAttribute()
    {
        return $this->getCurrentUserNotifications()
        ->where('read', false)
        ->count();
    }

    /**
     * Gets the exchange space notifictions count.
     *
     * @return void
     */
    public function getHasNotificationsAttribute()
    {
        return $this->notification_count > 0;
    }

    public function getCurrentUserNotifications()
    {
        return $this->getUserNotificationsForSpaceAndConversations($this);
    }

    public function getCurrentUserUnreadNotifications()
    {
        return $this->getUserNotificationsForSpaceAndConversations($this)
        ->where('read', false);
    }

    public function getFilesForDisplay()
    {
        return $this->getMedia()->reject(function ($file) {
            return Message::withTrashed()->find($file->getCustomProperty('message_id'))->deletedByAdmin();
        })->values()->map(function ($file) {
            $owner = User::find($file->getCustomProperty('user_id'));
            $file->full_url = $file->getFullUrl();
            $file->url = $file->getUrl();
            $file->date = $file->created_at->format('n/j/Y');
            $file->owner_name = optional($owner)->name;

            return $file;
        });
    }

    public function getTitleAttribute($value)
    {
        if (is_null(optional($this->currentMember)->custom_title)) {
            return $value;
        }

        return $this->currentMember->custom_title;
    }

    public function canAccessHistoricalFinancials()
    {
        return $this->historical_financials_public or $this->current_member->is_seller;
    }

    public function isRejected()
    {
        return $this->status === ExchangeSpaceStatusType::REJECTED;
    }

    public function transitioningFromInquiry()
    {
        $originalStatus = intval($this->getOriginal()['status']);
        $inquiryStatus = ExchangeSpaceStatusType::INQUIRY;
        $wasInquiry = $originalStatus === $inquiryStatus;
        $noLongerInquiry = $this->status !== $inquiryStatus;

        return $wasInquiry && $noLongerInquiry;
    }

    public function transitioningStatus()
    {
        return intval($this->status) !== intval($this->getOriginal()['status']);
    }

    public function transitioningHistoricalFinancialsPublicStatus()
    {
        $original = intval($this->getOriginal()['historical_financials_public']);
        $current = $this->historical_financials_public;

        return (bool) $current !== (bool) $original;
    }

    public function transitioningFinancialsPublicStatus()
    {
        $financials = $this->transitioningHistoricalFinancialsPublicStatus();

        return $financials;
    }

    public function financialsPublic()
    {
        return (bool) $this->historical_financials_public;
    }

    public function exitSurvey()
    {
        return $this->listing()->withTrashed()->first()->exitSurvey();
    }

    public function transitioningToSignedNDA()
    {
        $original = intval($this->getOriginal()['deal']);
        $current = intval($this->deal);
        $transitioning = $current !== $original;

        return $transitioning and ($current === ExchangeSpaceDealType::SIGNED_NDA);
    }

    public function transitioningDeal()
    {
        return intval($this->deal) !== intval($this->getOriginal()['deal']);
    }

    public function getTitleEditUrlAttribute()
    {
        return route('exchange-spaces.member-title.update', [
          'id' => $this->id,
        ]);
    }

    public function getDealPreNdaAttribute()
    {
        return $this->deal === ExchangeSpaceDealType::PRE_NDA;
    }

    public function getDealSignedNdaAttribute()
    {
        return $this->deal === ExchangeSpaceDealType::SIGNED_NDA;
    }

    public function getDealLoiSignedAttribute()
    {
        return $this->deal === ExchangeSpaceDealType::LOI_SIGNED;
    }

    public function getDealCompleteAttribute()
    {
        return $this->deal === ExchangeSpaceDealType::COMPLETE;
    }

    public function complete()
    {
        $this->deal = ExchangeSpaceDealType::COMPLETE;
        $this->status = ExchangeSpaceStatusType::COMPLETED;
    }

    public function getCongratulationsMessageAttribute()
    {
        return 'Congratulations on completing the sale of this business!  The seller now can close this listing, which will remove the listing from the site and close all related Exchange Spaces.  All participants are encouraged to export any files or conversations that they wish to retain, as the removal of the listing is permanent.';
    }

    public function closedUrl()
    {
        if ($this->isRejected()) {
            return route('business-inquiry.closed.show', ['id' => $this->id]);
        }

        return route('exchange-spaces.closed.show', ['id' => $this->id]);
    }


    public function clearNotifications()
    {
        // Clear the space notifications
        ExchangeSpaceNotification::where('exchange_space_id', $this->id)
        ->whereNotIn('type', [
            NotificationType::REJECTED_INQUIRY,
            NotificationType::DELETED_EXCHANGE_SPACE
        ])->get()->map->delete();

        // Clear the conversation notifications
        $this->conversations()->withTrashed()->get()->map->clearNotifications();
    }

    public function buyerHasLeft()
    {
        return !optional($this->buyerMember())->active;
    }

    public function closed()
    {
        if ($this->trashed()) {
            return true;
        }

        if ($this->buyerHasLeft() and !$this->current_member->is_seller) {
            return true;
        }

        return false;
    }

    public function accepted()
    {
        $isRejected = intval($this->status) === ExchangeSpaceStatusType::REJECTED;
        $isInquiry = intval($this->status) === ExchangeSpaceStatusType::INQUIRY;
        $notAccepted = $isInquiry or $isRejected;

        return !$notAccepted;
    }

    public function getBuyerUserIdAttribute()
    {
        return optional($this->buyer_user)->id;
    }

    public function advanceStage()
    {
        switch ($this->deal) {
            case ExchangeSpaceDealType::PRE_NDA:
                $this->deal = ExchangeSpaceDealType::SIGNED_NDA;
                break;

            case ExchangeSpaceDealType::SIGNED_NDA:
                $this->deal = ExchangeSpaceDealType::LOI_SIGNED;
                break;

            case ExchangeSpaceDealType::LOI_SIGNED:
                $this->complete();
                break;
        }

        $this->save();
    }

    public function getInquiryConversation()
    {
        // Try to get the first inquiry conversation...
        if ($inquiry = $this->conversations()->where('is_inquiry', 1)->first()) {
            return $inquiry;
        }

        // if not found just go with the first one since this should be the inquiry.
        return $this->conversations->first();
    }

    public function getInquiryConversationAttribute()
    {
        return $this->getInquiryConversation();
    }

    public function emptyHistoricalFinancials()
    {
        return collect([
            $this->listing->hf_most_recent_year,
            $this->listing->hf_most_recent_quarter,
        ])
        ->concat($this->listing->historicalFinancials)
        ->concat($this->listing->expenses)
        ->concat($this->listing->revenues)
        ->filter()
        ->isEmpty();
    }

    public function currentUserIsSeller()
    {
        return auth()->id() === $this->seller_user->id;
    }

    public function notificationUrl($type, $data = [])
    {
        $data = collect($data);

        switch (intval($type)) {
            case NotificationType::NEW_EXCHANGE_SPACE:
                return optional($this->inquiry_conversation)->show_url;
                break;

            case NotificationType::NEW_MEMBER_REQUESTED:
                $requestedMember = optional($data->get('requested_member'));
                return $this->showUrl(array_filter([
                    'member_user_id' => optional($requestedMember->user)->id,
                ]));
                break;

            case NotificationType::HISTORICAL_FINANCIAL_AVAILABLE:
                return route(
                    'exchange-spaces.historical-financials.show',
                    ['id' => $this->id]
                );
                break;

            case NotificationType::HISTORICAL_FINANCIAL_UNAVAILABLE:
                return route(
                    'exchange-spaces.historical-financials.show',
                    ['id' => $this->id]
                );
                break;

            default:
                return $this->show_url;
                break;
        }
    }

    public function useListingClosed()
    {
        return intval($this->listing->fresh()->deleted_by_space_id) !== intval($this->id);
    }

    public function unresolvedCount()
    {
        return $this->conversations->filter(function ($conversation) {
            return $conversation->latest_message && !$conversation->resolved;
        })->count();
    }

    public function shouldRedirectClosed()
    {
        // Check if its already the closed page. If so no need to redirect.
        if (parse_url(request()->url(), PHP_URL_PATH) === parse_url($this->closedUrl(), PHP_URL_PATH)) {
            return false;
        }

        // If the space has closed redirect everyone.
        if ($this->closed()) {
            return true;
        }

        // Redirect everyone except the seller if the buyer has left and the space is not closed.
        if ($this->buyerHasLeft() and !optional($this->currentMember)->is_seller) {
            return false;
        }

        return false;
    }
}
