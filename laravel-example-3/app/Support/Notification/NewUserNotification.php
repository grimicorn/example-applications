<?php

namespace App\Support\Notification;

use App\User;
use App\Support\Notification\Notification;
use App\Support\Notification\NotificationType;

class NewUserNotification extends Notification
{
    public function __construct(User $user, $data = [])
    {
        parent::__construct(NotificationType::NEW_USER);

        $this->setRecipient($user);
    }

    /**
     * {@inheritDoc}
     */
    public function save()
    {
        // This is an odd notification that will not be saved.
    }

    /**
     * {@inheritDoc}
     */
    public function emailBody()
    {
        return view('app.sections.notifications.email.new-user', [
            'profile_edit_url' => $this->recipient()->profile_edit_url,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function shouldEmail()
    {
        return true;
    }
}
