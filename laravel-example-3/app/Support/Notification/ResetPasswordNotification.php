<?php

namespace App\Support\Notification;

use App\Support\Notification\Notification;
use App\Support\Notification\NotificationType;

class ResetPasswordNotification extends Notification
{
    protected $user;
    protected $token;

    public function __construct($user, $token, $data = [])
    {
        $this->token = $token;
        $this->user = $user;

        parent::__construct(NotificationType::RESET_PASSWORD);

        $this->setRecipient($this->user);
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
        $email = urlencode($this->user->getEmailForPasswordReset());
        $link = url('password/reset', $this->token) . "?email={$email}";

        return view('app.sections.notifications.email.reset-password', [
            'user' => $this->user,
            'token' => $this->token,
            'reset_link' => $link,
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
