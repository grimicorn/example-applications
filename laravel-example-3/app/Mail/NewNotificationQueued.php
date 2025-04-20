<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Support\Notification\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewNotificationQueued extends NewNotification
{
    use Queueable;
}
