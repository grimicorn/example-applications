<?php

namespace App\Observers;

use App\SnapshotConfiguration;

class SnapshotConfigurationObserver
{
    /**
     * Handle the snapshot configuration "deleting" event.
     *
     * @param  \App\SnapshotConfiguration  $snapshotConfiguration
     * @return void
     */
    public function deleting(SnapshotConfiguration $snapshotConfiguration)
    {
        $snapshotConfiguration->snapshots()->get()->each->delete();
    }
}
