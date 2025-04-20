<?php

namespace App\Projectors;

use App\Job;
use App\StorableEvents\JobCreated;
use App\StorableEvents\JobDeleted;
use App\StorableEvents\JobUpdated;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

final class JobProjector implements Projector
{
    use ProjectsEvents;

    public function onJobCreated(JobCreated $event)
    {
        Job::create($event->attributes);
    }

    public function onJobUpdated(JobUpdated $event)
    {
        Job::uuid($event->uuid)
            ->fill($event->attributes)
            ->save();
    }

    public function onJobDeleted(JobDeleted $event)
    {
        Job::uuid($event->uuid)->delete();
    }
}
