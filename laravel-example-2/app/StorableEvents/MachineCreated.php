<?php

namespace App\StorableEvents;

use Spatie\EventProjector\ShouldBeStored;

class MachineCreated implements ShouldBeStored
{
    public $attributes;
    public $uuid;

    public function __construct(?array $attributes)
    {
        $this->uuid = $attributes['uuid'] ?? null;
        $this->attributes = $attributes;
    }
}
