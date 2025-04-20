<?php

namespace Tests\Unit;

use App\Job;
use Tests\TestCase;
use App\Enums\JobFlag;
use App\Domain\StoredEvent;
use App\Enums\Placement;
use App\Enums\WipStatus;
use Illuminate\Http\UploadedFile;
use App\StorableEvents\JobCreated;
use App\StorableEvents\JobDeleted;
use App\StorableEvents\JobUpdated;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use App\StorableEvents\JobPrintDetailDeleted;
use App\StorableEvents\JobPrintDetailUpdated;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_uploads_the_print_details_from_request_attributes()
    {
        Storage::fake('public');
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();
        $job->updatePrintDetailFromAttributes([
            'print_detail' => UploadedFile::fake()->create('uploadedDetails.pdf'),
        ]);

        $media = $job->fresh()->print_detail;
        $directory = collect(Storage::disk('public')->allDirectories())->first();
        $this->assertNotNull($media);
        $this->assertEquals('uploadedDetails.pdf', $media->file_name);
        $this->assertTrue(Storage::disk('public')->exists("{$directory}/uploadedDetails.pdf"));
    }

    /** @test */
    public function it_replaces_the_print_details_from_request_attributes()
    {
        Storage::fake('public');
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $job->updatePrintDetailFromAttributes([
            'print_detail' => $file1 = UploadedFile::fake()->create('replacedDetails1.pdf'),
        ]);

        $media = $job->fresh()->print_detail;
        $directory1 = collect(Storage::disk('public')->allDirectories())->first();
        $this->assertNotNull($media);
        $this->assertEquals('replacedDetails1.pdf', $media->file_name);
        $this->assertTrue(Storage::disk('public')->exists("{$directory1}/replacedDetails1.pdf"));

        $job->updatePrintDetailFromAttributes([
            'print_detail' => UploadedFile::fake()->create('details2.pdf'),
        ]);

        $media = $job->fresh()->print_detail;
        $directory2 = collect(Storage::disk('public')->allDirectories())->first();
        $this->assertNotNull($media);
        $this->assertEquals('details2.pdf', $media->file_name);
        $this->assertFalse(Storage::disk('public')->exists("{$directory1}/details.pdf"));
        $this->assertTrue(Storage::disk('public')->exists("{$directory2}/details2.pdf"));
    }

    /** @test */
    public function it_deletes_the_print_details_from_request_attributes()
    {
        Storage::fake('public');
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();
        $file = UploadedFile::fake()->create('details.pdf');
        $job->addMedia($file)->toMediaCollection('print_detail');
        $this->assertEquals('details.pdf', $job->print_detail->file_name);

        $directory = collect(Storage::disk('public')->allDirectories())->first();
        $this->assertTrue(Storage::disk('public')->exists("{$directory}/details.pdf"));

        $job->updatePrintDetailFromAttributes([
            'print_detail' => null,
        ]);

        $media = $job->fresh()->getFirstMedia('print_detail');
        $this->assertNull($media);
        $this->assertFalse(Storage::disk('public')->exists('1/details.pdf'));
    }

    /** @test */
    public function it_gets_the_print_detail_as_an_attribute()
    {
        $job = create(Job::class);
        $file = UploadedFile::fake()->create('details.pdf');
        $job->addMedia($file)->toMediaCollection('print_detail');
        $this->assertEquals('details.pdf', $job->print_detail->file_name);
    }

    /** @test */
    public function it_fires_a_job_created_event_when_creating_a_job()
    {
        $this->signInDeveloper();

        Event::fake([
            JobCreated::class,
        ]);

        $attributes = array_filter(make(Job::class, ['uuid' => null])->toArray());
        $job = Job::create($attributes);
        $attributes['uuid'] = $job->uuid;

        Event::assertDispatched(JobCreated::class, function ($event) use ($attributes) {
            $this->assertEquals($attributes, $event->attributes);
            $this->assertEquals($event->attributes['uuid'], $event->uuid);

            return true;
        });
    }

    /** @test */
    public function it_stores_a_job_created_event_when_creating_a_job()
    {
        $user = $this->signInAdmin();

        $attributes = collect(make(Job::class, ['uuid' => null])->toArray())->except($this->mergeWithJobAppends())->filter();
        $job = Job::create($attributes->toArray())->freshFromUuid();

        $event = StoredEvent::where('event_class', JobCreated::class)->first();
        $attributes = collect($attributes)->put('uuid', $job->uuid);
        $this->assertEquals($job->uuid, $event->event_properties['uuid']);
        $this->assertEquals($attributes->toArray(), $event->event_properties['attributes']);
        $this->assertEquals($user->id, $event->meta_data['user_id']);
        $this->assertArraySubset($attributes->toArray(), $job->fresh()->toArray());
    }

    /** @test */
    public function it_fires_a_job_updated_event_when_updating_a_job()
    {
        $this->signInDeveloper();

        Event::fake([
            JobUpdated::class,
        ]);

        $job = create(Job::class)->freshFromUuid();
        $attributes = collect(
            factory(Job::class)->state('alternate-only-updatable')->make()->toArray()
        )->filter()->except('type')->toArray();
        $job->update($attributes);

        Event::assertDispatched(JobUpdated::class, function ($event) use ($attributes, $job) {
            $this->assertEquals($attributes, $event->attributes);
            $this->assertEquals($job->uuid, $event->uuid);

            return true;
        });
    }

    /** @test */
    public function it_gets_the_is_specialty_attribute_for_a_specialty_job()
    {
        $job = factory(Job::class)->state('specialty')->create();

        $this->assertTrue($job->is_specialty);
    }

    /** @test */
    public function it_gets_the_is_specialty_attribute_for_a_default_job()
    {
        $job = factory(Job::class)->create();

        $this->assertFalse($job->is_specialty);
    }


    /** @test */
    public function it_stores_a_job_updated_event_when_updating_a_job()
    {
        $user = $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();
        $attributes = collect(
            factory(Job::class)->state('alternate-only-updatable')->make()->toArray()
        )
        ->filter()
        ->except($this->mergeWithJobAppends([
            'type'
        ]));
        $job->update($attributes->toArray());

        $event = StoredEvent::where('event_class', JobUpdated::class)->first();
        $this->assertEquals($job->uuid, $event->event_properties['uuid']);
        $this->assertArraySubset($attributes->toArray(), $event->event_properties['attributes']);
        $this->assertEquals($user->id, $event->meta_data['user_id']);
        $this->assertArraySubset($attributes->toArray(), $job->fresh()->toArray());
    }

    /** @test */
    public function it_fires_a_job_deleted_event_when_deleting_a_job()
    {
        $this->signInDeveloper();

        Event::fake([
            JobDeleted::class,
        ]);

        $job = create(Job::class)->freshFromUuid();
        $job->delete();

        Event::assertDispatched(JobDeleted::class, function ($event) use ($job) {
            $this->assertEquals($job->uuid, $event->uuid);

            return true;
        });
    }

    /** @test */
    public function it_stores_a_job_deleted_event_when_deleting_a_job()
    {
        $user = $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();
        $job->delete();

        $event = StoredEvent::where('event_class', JobDeleted::class)->first();
        $this->assertEquals($job->uuid, $event->event_properties['uuid']);
        $this->assertEquals($user->id, $event->meta_data['user_id']);
        $this->assertTrue($job->fresh()->trashed());
    }

    /** @test */
    public function it_fires_a_job_print_detail_updated_event_when_uploading_a_jobs_print_detail()
    {
        Event::fake([
            JobPrintDetailUpdated::class,
        ]);

        $job = create(Job::class)->freshFromUuid();
        $job->updatePrintDetailFromAttributes([
            'print_detail' => UploadedFile::fake()->create('file.pdf')
        ]);


        Event::assertDispatched(JobPrintDetailUpdated::class, function ($event) use ($job) {
            return $job->uuid === $event->jobUuid and
                $event->currentMediaAttributes['file_name'] === 'file.pdf' and
                $event->previousMediaAttributes === [];
        });
    }

    /** @test */
    public function it_stores_a_job_print_detail_updated_event_when_uploading_a_jobs_print_detail()
    {
        $user = $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();
        $job->updatePrintDetailFromAttributes([
            'print_detail' => UploadedFile::fake()->create('file.pdf')
        ]);

        $event = StoredEvent::where('event_class', JobPrintDetailUpdated::class)->first();
        $this->assertEquals($job->uuid, $event->event_properties['jobUuid']);
        $this->assertEmpty($event->event_properties['previousMediaAttributes']);
        $this->assertEquals('file.pdf', $event->event_properties['currentMediaAttributes']['file_name']);
        $this->assertEquals($user->id, $event->meta_data['user_id']);
    }

    /** @test */
    public function it_fires_a_job_print_detail_updated_event_when_updating_a_jobs_print_detail()
    {
        $job = create(Job::class)->freshFromUuid();
        $job->updatePrintDetailFromAttributes([
            'print_detail' => UploadedFile::fake()->create('file.pdf')
        ]);

        Event::fake([
            JobPrintDetailUpdated::class,
        ]);

        $job->updatePrintDetailFromAttributes([
            'print_detail' => UploadedFile::fake()->create('file2.pdf')
        ]);

        Event::assertDispatched(JobPrintDetailUpdated::class, function ($event) use ($job) {
            return $job->uuid === $event->jobUuid and
                $event->currentMediaAttributes['file_name'] === 'file2.pdf' and
                $event->previousMediaAttributes['file_name'] === 'file.pdf';
        });
    }

    /** @test */
    public function it_stores_a_job_print_detail_updated_event_when_updating_a_jobs_print_detail()
    {
        $user = $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();
        $job->updatePrintDetailFromAttributes([
            'print_detail' => UploadedFile::fake()->create('file.pdf')
        ]);

        $event = StoredEvent::where('event_class', JobPrintDetailUpdated::class)->first();
        $this->assertEquals($job->uuid, $event->event_properties['jobUuid']);
        $this->assertEmpty($event->event_properties['previousMediaAttributes']);
        $this->assertEquals('file.pdf', $event->event_properties['currentMediaAttributes']['file_name']);
        $this->assertEquals($user->id, $event->meta_data['user_id']);
    }

    /** @test */
    public function it_fires_a_job_print_detail_deleted_event_when_deleting_a_jobs_print_detail()
    {
        $job = create(Job::class)->freshFromUuid();
        $job->updatePrintDetailFromAttributes([
            'print_detail' => UploadedFile::fake()->create('file.pdf')
        ]);

        Event::fake([
            JobPrintDetailDeleted::class,
        ]);

        $job->updatePrintDetailFromAttributes([
            'print_detail' => null
        ]);

        Event::assertDispatched(JobPrintDetailDeleted::class, function ($event) use ($job) {
            return $job->uuid === $event->jobUuid and
                $event->mediaAttributes['file_name'] === 'file.pdf';
        });
    }

    /** @test */
    public function it_stores_a_job_print_detail_deleted_event_when_deleting_a_jobs_print_detail()
    {
        $user = $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();
        $job->updatePrintDetailFromAttributes([
            'print_detail' => UploadedFile::fake()->create('file.pdf')
        ]);
        $job->updatePrintDetailFromAttributes([
            'print_detail' => null
        ]);

        $event = StoredEvent::where('event_class', JobPrintDetailDeleted::class)->first();
        $this->assertEquals($job->uuid, $event->event_properties['jobUuid']);
        $this->assertEquals('file.pdf', $event->event_properties['mediaAttributes']['file_name']);
        $this->assertEquals($user->id, $event->meta_data['user_id']);
    }

    /** @test */
    public function it_get_all_of_a_jobs_control_numbers()
    {
        $job = create(Job::class);

        $this->assertEquals(
            [
                $job->control_number_1,
                $job->control_number_2,
                $job->control_number_3,
                $job->control_number_4,
            ],
            $job->control_numbers->toArray()
        );
    }

    /** @test */
    public function it_gets_the_control_numbers_label_attribute()
    {
        $job = create(Job::class);

        $this->assertEquals(
            implode(', ', [
                $job->control_number_1,
                $job->control_number_2,
                $job->control_number_3,
                $job->control_number_4,
            ]),
            $job->control_numbers_label
        );
    }

    /** @test */
    public function it_orders_jobs_by_due_at()
    {
        $dates = collect([
            now()->subDays(1),
            now(),
            now()->addDays(1),
            now()->addDays(2),
            now()->addDays(5),
        ]);
        $dates->shuffle()->each(function ($date) {
            create(Job::class, ['due_at' => $date]);
        });

        $this->assertEquals(
            $dates->sort()->map->format('Y-m-d H:i:s')->values()->toArray(),
            Job::all()->pluck('due_at')->map->format('Y-m-d H:i:s')->toArray()
        );
    }

    /** @test */
    public function it_gets_a_jobs_flag_slug()
    {
        $job = create(Job::class, ['flag' => JobFlag::HOT_MARKET]);
        $this->assertEquals('hot-market', $job->flag_slug);
    }

    /** @test */
    public function it_gets_a_jobs_impressions_count()
    {
        $job = create(Job::class, [
            'screens_1' => $screens_1 = 1,
            'screens_2' => $screens_2 = 2,
            'screens_3' => $screens_3 = 3,
            'screens_4' => $screens_4 = 4,
            'total_quantity' => $totalQuantity = 114,
            'small_quantity' => 18,
            'medium_quantity' => 22,
            'large_quantity' => 34,
            'xlarge_quantity' => 24,
            '2xlarge_quantity' => 16,
            'other_quantity' => 0,
        ]);

        $totalScreens = $screens_1 + $screens_2 + $screens_3 + $screens_4;

        $this->assertEquals(
            $totalQuantity * $totalScreens,
            $job->impressions_count
        );
    }

    /** @test */
    public function it_gets_a_jobs_screens_count()
    {
        $job = create(Job::class, [
            'screens_1' => 1,
            'screens_2' => 4,
            'screens_3' => 2,
            'screens_4' => 3,
        ]);

        $this->assertEquals(10, $job->screens_count);
    }

    /** @test */
    public function it_has_an_is_ready_attribute()
    {
        $readyJob = create(Job::class, ['wip_status' => WipStatus::K]);
        $this->assertTrue($readyJob->is_ready);

        collect(WipStatus::keys())->each(function ($status) {
            if ($status === WipStatus::K) {
                return;
            }

            $notReadyJob = create(Job::class, ['wip_status' => $status]);
            $this->assertFalse($notReadyJob->is_ready);
        });
    }

    /** @test */
    public function it_has_front_screen_count_attribute()
    {
        $job = create(Job::class, [
            'screens_1' => 2,
            'placement_1' => Placement::FRONT,
            'screens_2' => 5,
            'placement_2' => Placement::FRONT,
            'placement_3' => Placement::LEFT_CHEST,
            'placement_4' => Placement::RIGHT_CHEST,
        ]);

        $this->assertEquals(7, $job->front_screen_count);
    }

    /** @test */
    public function it_has_back_screen_count_attribute()
    {
        $job = create(Job::class, [
            'screens_1' => 2,
            'placement_1' => Placement::BACK,
            'screens_2' => 5,
            'placement_2' => Placement::BACK,
            'placement_3' => Placement::LEFT_CHEST,
            'placement_4' => Placement::RIGHT_CHEST,
        ]);

        $this->assertEquals(7, $job->back_screen_count);
    }

    /** @test */
    public function it_has_other_screen_count_attribute()
    {
        $otherKeys = collect(Placement::keys())
            ->reject(function ($key) {
                return $key === Placement::FRONT or $key === Placement::BACK;
            });

        $job = create(Job::class, [
            'screens_1' => 2,
            'placement_1' => $otherKeys->random(),
            'screens_2' => 5,
            'placement_2' => $otherKeys->random(),
            'placement_3' => Placement::FRONT,
            'placement_4' => Placement::BACK,
        ]);

        $this->assertEquals(7, $job->other_screen_count);
    }

    /** @test */
    public function it_stores_due_at_as_the_end_of_the_day()
    {
        $job = create(Job::class, [
            'due_at' => $expected = Carbon::create(2019, 4, 22, 6, 30, 22),
        ])->freshFromUuid();
        $this->assertEquals(
            $expected->endOfDay()->format('Y-m-d H:i:s'),
            $job->due_at->format('Y-m-d H:i:s')
        );
    }

    /** @test */
    public function it_stores_start_at_as_the_start_of_the_day()
    {
        $job = create(Job::class, [
            'start_at' => $expected = Carbon::create(2019, 4, 22, 6, 30, 22),
        ])->freshFromUuid();
        $this->assertEquals(
            $expected->startOfDay()->format('Y-m-d H:i:s'),
            $job->start_at->format('Y-m-d H:i:s')
        );
    }
}
