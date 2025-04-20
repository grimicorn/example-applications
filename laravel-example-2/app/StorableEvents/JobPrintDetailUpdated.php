<?php

namespace App\StorableEvents;

use Spatie\EventProjector\ShouldBeStored;

class JobPrintDetailUpdated implements ShouldBeStored
{
    public $jobUuid;
    public $currentMediaAttributes;
    public $previousMediaAttributes;
    public $userId;

    public function __construct(string $jobUuid, array $currentMediaAttributes, array $previousMediaAttributes = [])
    {
        $this->jobUuid = $jobUuid;
        $this->currentMediaAttributes = $currentMediaAttributes;
        $this->previousMediaAttributes = $previousMediaAttributes;
        $this->userId = auth()->id();
    }
}
