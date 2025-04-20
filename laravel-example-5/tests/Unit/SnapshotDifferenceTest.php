<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\SnapshotConfiguration;

// phpcs:ignorefile
class SnapshotDifferenceTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_calculates_the_difference_between_a_new_snapshot_and_the_baseline()
    {
        $configuration = create(SnapshotConfiguration::class);
        $baseline = $configuration->updateSnapshot();
        $new = $configuration->updateSnapshot()->fresh();
        $this->assertNotNull($new->fresh()->getFirstMedia('difference'));
        $this->assertNotNull($new->difference);
        $this->assertLessThanOrEqual(1, $new->difference);
        $this->assertGreaterThan(0, $new->difference);
    }

    /**
    * @test
    */
    public function it_does_not_calculate_the_difference_for_the_baseline_snapshot()
    {
        $configuration = create(SnapshotConfiguration::class);
        $baseline = $configuration->updateSnapshot()->fresh();
        $this->assertNull($baseline->getFirstMedia('difference'));
        $this->assertNull($baseline->difference);
    }
}
