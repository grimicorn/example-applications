<?php

namespace App\Domain;

use Spatie\EventProjector\Models\StoredEvent as BaseStoredEvent;

class StoredEvent extends BaseStoredEvent
{
    public static function boot()
    {
        parent::boot();

        static::creating(function (StoredEvent $storedEvent) {
            $storedEvent->meta_data['user_id'] = auth()->user()->id ?? null;
        });
    }
}
