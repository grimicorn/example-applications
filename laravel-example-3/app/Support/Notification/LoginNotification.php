<?php

namespace App\Support\Notification;

use App\Support\DeviceDetails;
use App\Support\Notification\Notification;
use App\Support\Notification\NotificationType;

class LoginNotification extends Notification
{
    public function __construct($data = [])
    {
        parent::__construct(NotificationType::LOGIN);
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
    public function emailSubject()
    {
        return NotificationType::emailSubject($this->type);
    }

    /**
     * {@inheritDoc}
     */
    public function emailBody()
    {
        $device = (new DeviceDetails)->get();

        return view('app.sections.notifications.email.login', [
            'timestamp' => $device->get('timestamp'),
            'location' => $device->get('location'),
            'ip_address' => $device->get('ip_address'),
            'browser' => $device->get('browser'),
            'operating_system' => $device->get('operating_system'),
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function shouldNotify()
    {
        // Override so we dont have to sleep 30 seconds
        if (app()->environment('testing')) {
            return true;
        }

        $user = optional(auth()->user());

        return !optional($user->created_at)->gte(now()->subSeconds(30));
    }

    /**
     * {@inheritDoc}
     */
    public function shouldEmail()
    {
        return $this->enabledLogin() and $this->shouldNotify();
    }

    /**
     * Checks if the enable login notification is enabled
     *
     * @return boolean
     */
    protected function enabledLogin()
    {
        return $this->recipient()->emailNotificationSettings->enable_login;
    }
}
