<?php

namespace App\Support\Notification;

use App\User;
use App\SavedSearchNotification;
use App\ConversationNotification;
use App\ExchangeSpaceNotification;
use App\Support\Notification\NotificationType;
use App\Support\Notification\NotificationTypeGroup;
use App\Support\Notification\HasSavedSearchNotifications;
use App\Support\Notification\HasConversationNotifications;
use App\Support\Notification\HasExchangeSpaceNotifications;

trait HasNotifications
{
    use HasConversationNotifications;
    use HasExchangeSpaceNotifications;
    use HasSavedSearchNotifications;

    public function getUrlAttribute()
    {
        $url = '';
        switch (with(new static)->getTable()) {
            case 'exchange_space_notifications':
                $url = $this->getExchangeSpaceShowUrl();
                break;

            case 'conversation_notifications':
                $url = $this->getConversationShowUrl();
                break;

            case 'saved_search_notifications':
                $url = $this->getSavedSearchShowUrl();
                break;
        }

        return $url ? $url : '';
    }

    /**
     * Gets the notifications slug attribute.
     *
     * @return string
     */
    public function getSlugAttribute()
    {
        return NotificationType::getSlug($this->type);
    }

    /**
     * Gets the notifications dashboard message.
     *
     * @return string
     */
    public function getMessage()
    {
        return NotificationType::getMessage($this);
    }

    /**
     * Gets the notifications dashboard message body.
     *
     * @return string
     */
    public function getMessageBodyAttribute()
    {
        return $this->getMessage()->getBody();
    }

    /**
     * Gets the notifications dashboard message icon.
     *
     * @return string
     */
    public function getMessageIconAttribute()
    {
        return $this->getMessage()->getIcon();
    }

    /**
     * Dispatches the notification.
     *
     * @param Notification $notification
     * @return void
     */
    public function dispatchNotification(Notification $notification)
    {
        // Save the notification
        $notification->saveNotification();

        // Send email
        $notification->sendMail();
    }

    /**
     * Gets a notification by its type.
     *
     * @param int $id
     * @param int $type
     * @return void
     */
    public function getNotificationByType($id, $type)
    {
        $model = NotificationType::getModel($type);

        if (is_null($model)) {
            return;
        }

        return $model::findOrFail($id);
    }

    /**
     * Gets current user notifications.
     *
     * @param integer $perPage
     * @param integer $user_id
     * @return \Illuminate\Support\Collection
     */
    protected function getUserNotifications($perPage = null)
    {
        $spaces = $this->getUserExchangeSpaceNotifications();
        $conversations = $this->getUserConversationNotifications();
        $savedSearch = $this->getSavedSearchNotifications();
        $notifications = $spaces->concat($conversations)
                         ->concat($savedSearch)
                         ->sortByDesc('created_at')
                         ->values();

        if (intval($perPage) <= 0) {
            return $notifications;
        }

        return $notifications->paginate($perPage);
    }

    /**
     * Gets a users notifications for an exchange space.
     *
     * @param App\ExchangeSpace space
     * @param int $user_id
     * @return void
     */
    protected function getUserNotificationsForSpaceAndConversations($space, $user_id = null)
    {
        return $this->getUserNotificationsForExchangeSpace(
            $space,
            $user_id
        )
        ->concat($this->getUserSpaceConversationNotifications(
            $space,
            $user_id
        ))
        ->sortByDesc('created_at')
        ->values();
    }

    /**
     * Gets notification types for exchange spaces.
     *
     * @return array
     */
    public function getSpaceNotificationTypes()
    {
        return NotificationType::getNotificationGroup(NotificationTypeGroup::EXCHANGE_SPACE);
    }

    /**
     * Gets notification types for conversations.
     *
     * @return array
     */
    public function getConversationNotificationTypes()
    {
        return NotificationType::getNotificationGroup(NotificationTypeGroup::CONVERSATION);
    }

    /**
     * Deletes all of a users notifications.
     *
     * @param User $user
     * @return void
     */
    public function deleteUserNotifications(User $user)
    {
        ExchangeSpaceNotification::where('user_id', $user->id)->get()->each->delete();
        ConversationNotification::where('user_id', $user->id)->get()->each->delete();
        SavedSearchNotification::where('user_id', $user->id)->get()->each->delete();
    }
}
