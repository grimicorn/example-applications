<?php

namespace App\Observers;

use App\Message;
use App\Support\Notification\HasNotifications;
use App\Support\Notification\NotificationType;

class MessageObserver
{
    use HasNotifications;

    /**
     * Listen to the User created event.
     *
     * @param  Message  $message
     * @return void
     */
    public function created(Message $message)
    {
        // Set the  notification
        $this->dispatchConversationNotifications($message->conversation, NotificationType::MESSAGE);
    }
}
