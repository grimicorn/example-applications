<?php

namespace Tests\Feature;

use App\Snapshot;
use Tests\TestCase;
use App\SnapshotConfiguration;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignoreFile
class SnapshotConfigurationTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_deletes_all_snapshots_when_deleting_a_snapshot_configuration()
    {
        $configuration = create(SnapshotConfiguration::class);
        create(Snapshot::class, [
            'snapshot_configuration_id' => $configuration->id,
        ], 5);
        $snapshots = $configuration->snapshots()->get();
        $this->assertNotEmpty($snapshots);

        $configuration->delete();

        $snapshots->each(function ($item) {
            $this->assertNull($item->fresh());
        });
    }
}
