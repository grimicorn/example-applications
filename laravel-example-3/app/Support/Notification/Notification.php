<?php

namespace App\Support\Notification;

use App\User;
use App\Mail\NewNotification;
use App\Mail\NewNotificationQueued;
use Illuminate\Support\Facades\Mail;
use App\Support\Notification\NotificationType;

abstract class Notification
{
    protected $type;
    protected $data;
    protected $recipient;

    public function __construct($type = null, $data = [])
    {
        $this->type = $type;
        $this->data = $data;

        // Send it to the current logged in user if we can.
        // If not then it will need to be set manually.
        if (auth()->check()) {
            $this->setrecipient(auth()->user());
        }
    }

    /**
     * Handles saving the message.
     *
     * @return string
     */
    abstract public function save();

    /**
     * Saves the notification to the database
     *
     * @return void
     */
    public function saveNotification()
    {
        if ($this->shouldNotify()) {
            $this->save();
        }
    }

    /**
     * The email subject.
     *
     * @return string
     */
    public function emailSubject()
    {
        return NotificationType::emailSubject($this->type);
    }

    /**
     * The email header
     *
     * @return string
     */
    public function emailHeader()
    {
        return 'Dear ' . $this->recipient()->first_name . ',';
    }

    /**
     * The email body message content.
     *
     * @return string
     */
    abstract public function emailBody();

    /**
     * The email footer
     *
     * @return string
     */
    public function emailFooter()
    {
        return '<p style="clear:both;">Best regards,<br>The Firm Exchange Team</p>';
    }

    /**
     * The email sub-footer
     *
     * @return string
     */
    public function subFooter()
    {
        return view('emails.sub-footer');
    }

    /**
     * Sends the email.
     *
     * @return void
     */
    public function sendMail()
    {
        if ($this->shouldEmail()) {
            Mail::to($this->recipient())
                ->send($this->getMailNewNotification());
        }
    }

    /**
     * Gets the notification email
     *
     * @return NewNotification
     */
    protected function getMailNewNotification()
    {
        // Testing send non queued notifications.
        if (app()->environment('testing')) {
            return new NewNotification($this);
        }

        // Some notifications should not be queued.
        $non_queue = collect([
            NotificationType::LOGIN,
        ])->contains($this->type);
        if ($non_queue) {
            return new NewNotification($this);
        }

        return new NewNotificationQueued($this);
    }

    /**
     * The set the user that will receive the notification.
     *
     * @param App\User $recipient
     *
     * @return App\User
     */
    public function setrecipient(User $recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * The user that will receive the notification.
     *
     * @return App\User
     */
    public function recipient()
    {
        return $this->recipient;
    }

    /**
     * The notification type.
     *
     * @return int
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * If the notification should send an notify.
     *
     * @return boolean
     */
    public function shouldNotify()
    {
        return true;
    }

    /**
     * If the notification should send an email.
     *
     * @return boolean
     */
    public function shouldEmail()
    {
        return optional($this->recipient()->emailNotificationSettings)
        ->enable_all;
    }

    /**
     * Gets a view from the notification type.
     *
     * @return string
     */
    public function getViewSlugFromType()
    {
        return NotificationType::getSlug($this->type);
    }
}
