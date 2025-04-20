<?php

namespace App;

use App\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Support\ExchangeSpace\MemberRole;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\Notification\NotificationType;
use App\Support\Notification\HasNotifications;

class ExchangeSpaceMember extends BaseModel
{
    use SoftDeletes;
    use HasNotifications;

    protected $table = 'members';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'exit_message'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'role' => 'integer',
        'user_id' => 'integer',
        'exchange_space_id' => 'integer',
        'approved' => 'boolean',
        'active' => 'boolean',
        'dashboard' => 'boolean',
        'removed_by_seller' => 'boolean',
        'pending' => 'boolean',
    ];

    protected $appends = [
        'is_seller',
        'role_label',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the exchange space that owns the listing.
     */
    public function space()
    {
        return $this->belongsTo('App\ExchangeSpace', 'exchange_space_id')->withTrashed();
    }

    /**
     * Get the user that owns the listing.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Set the member's role.
     *
     * @param  string  $value
     * @return void
     */
    public function setRoleAttribute($value)
    {
        // Sellers should always be approved.
        if (MemberRole::SELLER == intval($value)) {
            $this->attributes['approved'] = true;
        }

        // Set the role value.
        $this->attributes['role'] = $value;
    }

    public function scopeOfCurrentUser($query)
    {
        return $query->where('user_id', '=', Auth::id());
    }

    public function scopeOnDashboard($query)
    {
        return $query->where('dashboard', '=', true);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopePending($query)
    {
        return $query->where('pending', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    public function scopeOfBuyer($query)
    {
        return $query->where('role', '=', MemberRole::BUYER);
    }

    public function scopeOfSeller($query)
    {
        return $query->where('role', '=', MemberRole::SELLER);
    }

    public function scopeOfUser($query, $user_id)
    {
        return $query->where('user_id', '=', $user_id);
    }

    /**
     * Gets the members's is_seller attribute.
     *
     * @return boolean
     */
    public function getIsSellerAttribute()
    {
        return $this->role === MemberRole::SELLER;
    }

    protected function getCustomTitleAttribute($value)
    {
        return $value ?: $this->space()->first()->getAttributes()['title'];
    }

    /**
     * Gets the members's not_seller attribute.
     *
     * @return boolean
     */
    public function getNotSellerAttribute()
    {
        return !$this->is_seller;
    }

    /**
     * Gets the members's not_buyer attribute.
     *
     * @return boolean
     */
    public function getNotBuyerAttribute()
    {
        return !$this->is_buyer;
    }


    /**
     * Gets the members's is_seller_advisor attribute.
     *
     * @return boolean
     */
    public function getIsSellerAdvisorAttribute()
    {
        return $this->role === MemberRole::SELLER_ADVISOR;
    }

    /**
     * Gets the members's is_buyer attribute.
     *
     * @return boolean
     */
    public function getIsBuyerAttribute()
    {
        return $this->role === MemberRole::BUYER;
    }

    /**
     * Gets the members's is_buyer_advisor attribute.
     *
     * @return boolean
     */
    public function getIsBuyerAdvisorAttribute()
    {
        return $this->role === MemberRole::BUYER_ADVISOR;
    }

    /**
     * Gets the member's role label attribute.
     *
     * @return boolean
     */
    public function getRoleLabelAttribute()
    {
        return MemberRole::getLabel($this->role);
    }

    public function getRequestedByAttribute()
    {
        return $this->getRequestedByMember();
    }

    public function getRequestedByMember()
    {
        $member = optional(ExchangeSpaceMember::where('id', $this->requested_by_id))->first();
        if (!$member) {
            $member = optional(ExchangeSpaceMember::where('user_id', auth()->id()))
            ->where('exchange_space_id', $this->exchange_space_id)
            ->first();
        }

        return $member;
    }

    public function canAccessSpace()
    {
        return $this->active and $this->approved;
    }

    public function canAccessClosedSpace()
    {
        if ($this->space->buyerHasLeft() and $this->approved) {
            return true;
        }

        if ($this->is_buyer) {
            return true;
        }

        return $this->canAccessSpace();
    }

    public function userRemovedByAdmin()
    {
        return (bool) optional($this->user()->withTrashed()->get()->first())->removed_by_admin;
    }

    public function deactivate($exit_message = null, $removedUser = null, $clearNotifications = true)
    {
        // If the current member is the seller we want to mark that the user was removed by the seller.
        if (optional(optional($this->space)->current_member)->is_seller) {
            $this->removed_by_seller = true;
        }

        // Deactivate the member
        $this->active = false;
        $this->exit_message = $exit_message;
        $this->dashboard = false;
        $this->pending = false;

        $this->save();

        $this->delete();

        if ($clearNotifications) {
            $this->clearNotifications();
        }

        // Handle the members notifications.
        if ($this->approved) {
            $this->sendDeactivatedNotification();
        }

        // Touch the spaces timestamp
        $this->space->touch();

        // If the deactivated member is a buyer then we need to deactivate every one else except the seller and the buyer.
        if ($this->is_buyer) {
            $this->space()->withTrashed()->first()->allMembers
            ->whereNotIn('role', [MemberRole::SELLER, MemberRole::BUYER])
            ->each->deactivate($exit_message, $this->user, false);
        }
    }

    public function getDeactivatedNotificationType()
    {
        if ($this->removed_by_seller) {
            return NotificationType::SELLER_REMOVED_ADVISOR;
        }

        // If not removed by seller then lets just use their role.
        switch ($this->role) {
            case MemberRole::BUYER_ADVISOR:
                return NotificationType::REMOVED_ADVISOR_BUYER;
                break;

            case MemberRole::BUYER:
                return NotificationType::REMOVED_BUYER;
                break;

            default:
                return NotificationType::REMOVED_ADVISOR_SELLER;
                break;
        }
    }

    public function sendDeactivatedNotification($removedUser = null)
    {
        $removedUser = $removedUser ?? $this->user;
        $type = $this->getDeactivatedNotificationType();
        $data = [
            'removed_member_name' => $removedUser->name,
            'removed_member' => optional(
                $this->space->allMembers()->withTrashed()->where('user_id', $removedUser->id)->first()
            ),
        ];
        $space = $this->space()->withTrashed()->first();

        // When a buyer is removed we also deactivate the other users since the exchange space is in a
        // weird semi-closed state since the only person left is the seller.
        // The buyer will always be removed first.
        if ($space->buyerHasLeft() and $type !== NotificationType::REMOVED_BUYER) {
            return;
        }

        // For the seller removed advisor we also have to send it to the removed member
        if ($type === NotificationType::SELLER_REMOVED_ADVISOR) {
            $this->dispatchExchangeSpaceNotification($this, $type, $data);
        }

        // We also have to notify all other users
        $this->dispatchExchangeSpaceNotifications($space, $type, $data);
    }


    public function activate()
    {
        // Set required values if needed
        $this->setRequestedBy()->setRole();

        // Restore if trashed
        if ($this->trashed()) {
            $this->restore();
        }

        // Set member as active
        $this->active = true;
        $this->approved = true;
        $this->pending = false;
        $this->save();

        // Touch the spaces timestamp
        $this->space->touch();
    }

    public function setPending()
    {
        // Set required values if needed
        $this->setRequestedBy()->setRole();

        // Restore if trashed
        if ($this->trashed()) {
            $this->restore();
        }

        // Request the review
        $this->active = true;
        $this->approved = false;
        $this->pending = true;
        $this->save();

        // Touch the spaces timestamp
        $this->space->touch();
    }

    protected function clearNotifications()
    {
        $space = $this->space()->withTrashed()->first();

        // Delete the exchange space notifications
        ExchangeSpaceNotification::where('exchange_space_id', $space->id)
        ->where('user_id', $this->user->id)
        ->whereNotIn('type', [NotificationType::SELLER_REMOVED_ADVISOR])
        ->get()->each->delete();

        // Delete the conversation notifications
        ConversationNotification::whereIn('conversation_id', $space->conversations->pluck('id'))
        ->where('user_id', $this->user->id)->get()->each->delete();
    }

    public function fillAdministrator($userId, $spaceId)
    {
        $this->role = MemberRole::ADMINISTRATOR;
        $this->user_id = $userId;
        $this->exchange_space_id = $spaceId;
        $this->approved = true;
        $this->active = true;
        $this->dashboard = false;

        return $this;
    }

    public function requestedBySeller()
    {
        $requestedBy = $this->getRequestedByMember();
        if (is_null($requestedBy)) {
            $requestedBy = $this->space->current_member;
        }

        return (optional($requestedBy)->is_seller or optional($requestedBy)->is_seller_advisor);
    }

    public function setRequestedBy()
    {
        if (is_null($this->requested_by_id) or $this->trashed()) {
            $this->requested_by_id = optional($this->space->current_member)->id;
        }

        return $this;
    }

    public function setRole()
    {
        // Only set members that are new or that are trashed.
        if (is_null($this->role) or $this->trashed()) {
            // Set the seller/buyer requested by.
            if ($this->requestedBySeller()) {
                $this->role = $this->is_seller ? MemberRole::SELLER : MemberRole::SELLER_ADVISOR;
            } else {
                $this->role = $this->is_buyer ? MemberRole::BUYER : MemberRole::BUYER_ADVISOR;
            }
        }

        return $this;
    }

    public function getProfileUrlAttribute()
    {
        return route('professional.show', ['id' => $this->user->id]);
    }
}
