<?php

namespace App\StorableEvents;

use Spatie\EventProjector\ShouldBeStored;

final class MachineDeleted implements ShouldBeStored
{
    public $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }
}
