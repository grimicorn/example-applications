<?php
namespace App\Support\Notification;

use App\ExchangeSpace;
use App\ExchangeSpaceMember;
use App\ExchangeSpaceNotification as ESNModel;
use App\Support\Notification\ExchangeSpaceNotification;
use App\Support\Notification\ExchangeSpaceMemberNotification;

trait HasExchangeSpaceNotifications
{
    /**
     * Disapatch all of the member notifications.
     *
     * @param App\ExchangeSpace $space
     * @param int $type
     * @param array $data
     * @param boolean $once
     * @return void
     */
    public function dispatchExchangeSpaceNotifications(ExchangeSpace $space, int $type, array $data = [], bool $once = false)
    {
        $space->members()->whereNotIn('user_id', [auth()->id()])->get()
        ->each(function ($member) use ($type, $data, $space, $once) {
            $this->dispatchExchangeSpaceNotification($member, $type, $data, $space, $once);
        });
    }

    public function dispatchExchangeSpaceNotificationsOnce(ExchangeSpace $space, int $type, array $data = [])
    {
        return $this->dispatchExchangeSpaceNotifications($space, $type, $data, $once = true);
    }

    /**
     * Dispatch an individual member notification
     *
     * @param ExchangeSpaceMember $member
     * @param integer $type
     * @param array $data
     * @param boolean $once
     * @return void
     */
    public function dispatchExchangeSpaceNotification(ExchangeSpaceMember $member, int $type, array $data = [], ExchangeSpace $space = null, bool $once = false)
    {
        if ($once and !$this->firstTime($member, $type, $space)) {
            return;
        }

        $space = $space ?:$member->space()->withTrashed()->first();
        $notification = new ExchangeSpaceNotification($space, $type, $data);
        $notification->setRecipient($member->user);
        $this->dispatchNotification($notification);
    }

    protected function dispatchExchangeSpaceNotificationOnce(ExchangeSpaceMember $member, int $type, array $data = [], ExchangeSpace $space = null)
    {
        return $this->dispatchExchangeSpaceNotification($member, $type, $data, $space, $once = true);
    }

    /**
     * Disapatch all of the member notifications.
     *
     * @param \Illuminate\Support\Collection $members
     * @param int $type
     * @param array $data
     * @return void
     */
    public function dispatchNewExchangeSpaceMemberNotifications($newMember, $type, $data = [])
    {
        // Alert the current members
        $newMember->space->members
        ->whereNotIn('user_id', [$newMember->user->id, auth()->id()])
        ->each(function ($member) use ($newMember, $type, $data) {
            $notifications = new ExchangeSpaceMemberNotification(
                $newMember,
                $type,
                $data
            );
            $notifications->setRecipient($member->user);
            $this->dispatchNotification($notifications);
        });

        $this->dispatchExchangeSpaceNotification($newMember, NotificationType::EXCHANGE_SPACE_MEMBER_WELCOME, $data);
    }

    protected function getExchangeSpaceShowUrl()
    {
        // Disables links for removed members
        if (optional($this->member)->trashed()) {
            return '';
        }

        return optional($this->exchangeSpace)->notificationUrl($this->type, [
            'requested_member' => optional($this->requestedMember),
        ]);
    }

    /**
     * Gets a users notifications for an exchange space.
     *
     * @param App\ExchangeSpace $space
     * @param int $user_id
     * @return void
     */
    protected function getUserNotificationsForExchangeSpace($space, $user_id = null)
    {
        $user_id = is_null($user_id) ? auth()->id() : $user_id;
        return $notifications = ESNModel::where('user_id', $user_id)
        ->where('exchange_space_id', $space->id)
        ->get();
    }

    /**
     * Gets user exchange space notifications.
     *
     * @param integer $user_id
     * @return \Illuminate\Support\Collection
     */
    protected function getUserExchangeSpaceNotifications($user_id = null)
    {
        return ESNModel::where(
            'user_id',
            is_null($user_id) ? auth()->id() : $user_id
        )->get();
    }

    protected function firstTime($member, $type, $space)
    {
        return ESNModel::where([
            'user_id' => $member->user->id,
            'type' => $type,
            'exchange_space_id' => $space->id,
        ])->get()->isEmpty();
    }
}
