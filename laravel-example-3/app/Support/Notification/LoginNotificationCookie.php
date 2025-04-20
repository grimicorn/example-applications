<?php

namespace App\Support\Notification;

use Illuminate\Support\Facades\Cookie;

class LoginNotificationCookie
{
    public function get()
    {
        return Cookie::get($this->name());
    }

    public function set()
    {
        Cookie::queue(
            $this->name(),
            $value = true,
            $minutes = (24 * 60 * 365) * 10 // 10 years
        );
    }

    public function exists()
    {
        return !!$this->get();
    }

    public function name()
    {
        return 'fe_' . sha1('fe_previous_device_' . auth()->id() . config('app.key'));
    }
}
