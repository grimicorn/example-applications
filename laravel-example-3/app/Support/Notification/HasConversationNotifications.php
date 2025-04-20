<?php
namespace App\Support\Notification;

use App\Conversation;
use App\Support\Notification\ConversationNotification;
use App\ConversationNotification as CNModel;

trait HasConversationNotifications
{
    protected function getConversationShowUrl()
    {
        return optional($this->conversation)->show_url;
    }

    protected function dispatchConversationNotifications($conversation, $type, $data = [])
    {
        if (!$conversation->space) {
            return;
        }

        $conversation->space->members
        ->whereNotIn('user_id', [auth()->id()])
        ->each(function ($member) use ($conversation, $type, $data) {
            $notifications = new ConversationNotification(
                $conversation,
                $type,
                $data
            );
            $notifications->setRecipient($member->user);
            $this->dispatchNotification($notifications);
        });
    }


    /**
     * Gets a users notifications for a conversation.
     *
     * @param int $conversation_id
     * @param int $user_id
     * @return void
     */
    protected function getUserNotificationsForConversation($conversation_id, $user_id = null)
    {
        $user_id = is_null($user_id) ? auth()->id() : $user_id;
        return CNModel::where('user_id', $user_id)
        ->where('conversation_id', $conversation_id)->get();
    }

    /**
     * Gets user conversation notifications.
     *
     * @param integer $user_id
     * @return \Illuminate\Support\Collection
     */
    protected function getUserConversationNotifications($user_id = null)
    {
        return CNModel::where(
            'user_id',
            is_null($user_id) ? auth()->id() : $user_id
        )->get();
    }

    /**
     * Gets user conversation notifications for an exchange space.
     *
     * @param integer $user_id
     * @return \Illuminate\Support\Collection
     */
    protected function getUserSpaceConversationNotifications($space, $user_id = null)
    {
        $userId = is_null($user_id) ? auth()->id() : $user_id;
        $conversationIds = $space->conversations()->get()->pluck('id');

        return CNModel::where('user_id', $userId)
        ->whereIn('conversation_id', $conversationIds)
        ->get();
    }
}
