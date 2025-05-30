<?php

namespace App\Concerns;

use App\Notification;
use Illuminate\Notifications\Notifiable as BaseNotifiable;

trait Notifiable
{
    use BaseNotifiable;

    /**
     * Get the entity's notifications.
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')
                            ->orderBy('created_at', 'desc');
    }
}
