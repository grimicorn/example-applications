<?php

namespace App\Observers;

use App\Conversation;
use App\Support\ExchangeSpaceStatusType;
use App\Support\Notification\HasNotifications;
use App\Support\Notification\NotificationType;

class ConversationObserver
{
    use HasNotifications;

    /**
     * Listen to the User created event.
     *
     * @param  Conversation  $conversation
     * @return void
     */
    public function created(Conversation $conversation)
    {
        // $conversation->dispatchCreatedNotification();
    }
}
