<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Support\Notification\Notification;

class NewNotification extends Mailable
{
    use SerializesModels;

    public $notification;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.notification')
        ->with([
            'notification' => $this->notification,
        ])
        ->subject($this->notification->emailSubject());
    }
}
