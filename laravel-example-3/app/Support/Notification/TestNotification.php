<?php

namespace App\Support\Notification;

use Illuminate\Support\Carbon;
use App\Support\DetectDevice\Detect;
use App\Support\Notification\Notification;

/**
 * This class is only for testing the abstract Notification base class
 */
class TestNotification extends Notification
{
    /**
     * {@inheritDoc}
     */
    public function save()
    {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function emailBody()
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function emailSubject()
    {
        return '';
    }
}
