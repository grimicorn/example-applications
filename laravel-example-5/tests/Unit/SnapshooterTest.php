<?php

namespace Tests\Unit;

use App\SitePage;
use App\Snapshot;
use Tests\TestCase;
use App\Support\Snapshooter;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\SnapshotConfiguration;

// phpcs:ignorefile
class SnapshooterTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_takes_a_pages_snapshot_for_a_configuration()
    {
        $snapshooter = new Snapshooter(
            $configuration = create(SnapshotConfiguration::class)
        );
        $snapshot = $snapshooter->take();

        $this->assertInstanceOf(Snapshot::class, $snapshot);
        $this->assertEquals($configuration->id, $snapshot->snapshot_configuration_id);
        $this->assertFileExists($snapshot->path);
        $this->assertFileNotExists(
            storage_path('app/temp/' . $snapshooter->fileName())
        );
        $this->assertTrue($snapshot->is_baseline);
        $this->assertEquals(0, $snapshot->difference);
    }

    /**
    * @test
    */
    public function it_only_sets_the_initial_screens_to_baseline_for_a_configuration()
    {
        $configuration = create(SnapshotConfiguration::class);

        $snapshot1 = (new Snapshooter($configuration))->take();
        $this->assertTrue($snapshot1->is_baseline);

        $snapshot2 = (new Snapshooter($configuration))->take();
        $this->assertFalse($snapshot2->is_baseline);
        $this->assertTrue($snapshot1->fresh()->is_baseline);

        $snapshot3 = (new Snapshooter($configuration))->take();
        $this->assertFalse($snapshot3->is_baseline);
        $this->assertTrue($snapshot1->fresh()->is_baseline);
    }

    /**
    * @test
    */
    public function it_calculates_a_snapshots_difference_for_a_configuration()
    {
        $configuration = create(SnapshotConfiguration::class);

        $snapshot1 = (new Snapshooter($configuration))->take();
        $this->assertNull($snapshot1->difference);

        $snapshot2 = (new Snapshooter($configuration))->take();
        $this->assertNotNull($snapshot2->is_baseline);
        $this->assertLessThanOrEqual(1, $snapshot2->difference);
        $this->assertGreaterThan(0, $snapshot2->difference);
    }

    /**
    * @test
    */
    public function it_does_not_update_snapshots_for_ignored_pages_a_configuration()
    {
        $page1 = create(SitePage::class);
        $page2 = create(SitePage::class, ['ignored' => true]);
        $configuration1 = $page1->snapshotConfigurations()->first();
        $configuration2 = $page2->snapshotConfigurations()->first();

        $this->assertInstanceOf(
            Snapshot::class,
            (new Snapshooter($configuration1))->take()
        );

        $this->assertNull(
            (new Snapshooter($configuration2))->take()
        );
    }

    /**
    * @test
    */
    public function it_keeps_the_last_baseline_and_the_last_three_images_a_configuration()
    {
        $configuration = create(SnapshotConfiguration::class);

        $snapshot1 = (new Snapshooter($configuration))->take();
        $snapshot1Path = $snapshot1->path;
        $this->assertFileExists($snapshot1Path);
        $this->assertInstanceOf(Snapshot::class, $snapshot1);

        // Sleep for 1 second to allow the order by to work correctly
        sleep(1);

        $snapshot2 = (new Snapshooter($configuration))->take();
        $snapshot2Path = $snapshot2->path;
        $this->assertFileExists($snapshot2Path);
        $this->assertInstanceOf(Snapshot::class, $snapshot2);
        $snapshot2->setBaseline();

        $snapshot3 = (new Snapshooter($configuration))->take();
        $snapshot3Path = $snapshot3->path;
        $this->assertFileExists($snapshot3Path);
        $this->assertInstanceOf(Snapshot::class, $snapshot3);

        $snapshot4 = (new Snapshooter($configuration))->take();
        $snapshot4Path = $snapshot4->path;
        $this->assertFileExists($snapshot4Path);
        $this->assertInstanceOf(Snapshot::class, $snapshot4);

        $snapshot5 = (new Snapshooter($configuration))->take();
        $snapshot5Path = $snapshot5->path;
        $this->assertFileExists($snapshot5Path);
        $this->assertInstanceOf(Snapshot::class, $snapshot5);

        $snapshot6 = (new Snapshooter($configuration))->take();
        $snapshot6Path = $snapshot6->path;
        $this->assertFileExists($snapshot6Path);
        $this->assertInstanceOf(Snapshot::class, $snapshot6);


        $this->assertFileNotExists($snapshot1Path);
        $this->assertNull($snapshot1->fresh());
        $this->assertFileExists($snapshot2Path);
        $this->assertInstanceOf(Snapshot::class, $snapshot2);
        $this->assertFileNotExists($snapshot3Path);
        $this->assertNull($snapshot3->fresh());
        $this->assertFileExists($snapshot4Path);
        $this->assertInstanceOf(Snapshot::class, $snapshot4->fresh());
        $this->assertFileExists($snapshot5Path);
        $this->assertInstanceOf(Snapshot::class, $snapshot5->fresh());
        $this->assertFileExists($snapshot6Path);
        $this->assertInstanceOf(Snapshot::class, $snapshot6->fresh());
    }
}
