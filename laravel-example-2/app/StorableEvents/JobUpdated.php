<?php

namespace App\StorableEvents;

use Spatie\EventProjector\ShouldBeStored;

class JobUpdated implements ShouldBeStored
{
    public $attributes;
    public $uuid;

    public function __construct(string $uuid, array $attributes)
    {
        $this->attributes = $attributes;
        $this->uuid = $uuid;
    }
}
