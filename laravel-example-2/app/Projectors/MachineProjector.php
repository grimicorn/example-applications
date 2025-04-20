<?php

namespace App\Projectors;

use App\Machine;
use App\StorableEvents\MachineCreated;
use App\StorableEvents\MachineDeleted;
use App\StorableEvents\MachineUpdated;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

final class MachineProjector implements Projector
{
    use ProjectsEvents;

    public function onMachineCreated(MachineCreated $event)
    {
        Machine::create($event->attributes);
    }

    public function onMachineUpdated(MachineUpdated $event)
    {
        Machine::uuid($event->uuid)
            ->fill($event->attributes)
            ->save();
    }

    public function onMachineDeleted(MachineDeleted $event)
    {
        Machine::uuid($event->uuid)->delete();
    }
}
