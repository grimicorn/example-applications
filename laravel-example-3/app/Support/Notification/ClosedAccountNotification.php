<?php

namespace App\Support\Notification;

use App\User;
use App\Support\Notification\Notification;
use App\Support\Notification\NotificationType;

class ClosedAccountNotification extends Notification
{
    public function __construct(User $user, array $data = [])
    {
        parent::__construct(NotificationType::CLOSED_ACCOUNT);
        $this->user = $user;
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
        return view('app.sections.notifications.email.closed-account', [
            'register_url' => url('register'),
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function shouldEmail()
    {
        return !$this->user->removed_by_admin;
    }

    /**
     * {@inheritDoc}
     */
    public function subFooter()
    {
        return 'You are receiving this notification email as you were a registered user of firmexchange.com. Unless you rejoin the community, you will not receive any further email from Firm Exchange.';
    }
}
