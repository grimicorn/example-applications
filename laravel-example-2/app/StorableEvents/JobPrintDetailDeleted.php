<?php

namespace App\StorableEvents;

use Spatie\EventProjector\ShouldBeStored;

class JobPrintDetailDeleted implements ShouldBeStored
{
    public $jobUuid;
    public $mediaAttributes;
    public $userId;

    public function __construct(string $jobUuid, array $mediaAttributes)
    {
        $this->jobUuid = $jobUuid;
        $this->mediaAttributes = $mediaAttributes;
        $this->userId = auth()->id();
    }
}
