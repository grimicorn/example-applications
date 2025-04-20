<?php

namespace Tests\Feature;

use App\Enums\JobFlag;
use App\Job;
use App\Machine;
use Tests\TestCase;
use App\Enums\JobType;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JobsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_retrieves_a_paginated_list_of_all_jobs_if_not_filters_are_applied()
    {
        $this->withoutExceptionHandling();
        $this->signInAdmin();

        $jobs = create(Job::class, [], 30)->map->freshFromUuid();
        $json = $this->json('get', '/jobs', [])
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertCount(config('domain.jobs_per_page'), $jsonJobs);
        $this->assertEquals(
            $jobs->take(config('domain.jobs_per_page'))->pluck('id')->sort()->values()->toArray(),
            $jsonJobs->pluck('id')->sort()->values()->toArray()
        );
    }

    /** @test */
    public function it_filters_jobs_by_machine_id()
    {
        $this->signInAdmin();

        $machines = create(Machine::class, [], 4)->map->freshFromUuid();
        $jobs = create(Job::class, [], 20)->map->freshFromUuid();
        $machine_id = $jobs->pluck('machine_id')->random();

        $json = $this->json('get', route('jobs.index', [
                'filter[machine_id]' => $machine_id,
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $expectedJobs = $jobs->where('machine_id', $machine_id)->take(config('domain.jobs_per_page'));
        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertCount($expectedJobs->count(), $jsonJobs);
        $this->assertNotEmpty($jsonJobs);
        $this->assertEquals(
            $expectedJobs->pluck('id')->toArray(),
            $jsonJobs->pluck('id')->toArray()
        );
    }

    /** @test */
    public function it_filters_jobs_by_wip_status()
    {
        $this->signInAdmin();

        $jobs = factory(Job::class, [], 20)->state('random')->create()->map->freshFromUuid();
        $wip_status = $jobs->pluck('wip_status')->random();

        $json = $this->json('get', route('jobs.index', [
                'filter[wip_status]' => $wip_status,
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $expectedJobs = $jobs->where('wip_status', $wip_status)->take(config('domain.jobs_per_page'));
        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertCount($expectedJobs->count(), $jsonJobs);
        $this->assertNotEmpty($jsonJobs);
        $this->assertEquals(
            $expectedJobs->pluck('id')->toArray(),
            $jsonJobs->pluck('id')->toArray()
        );
    }

    /** @test */
    public function it_filters_jobs_by_full_work_order_number()
    {
        $this->signInAdmin();

        $jobs = factory(Job::class, [], 20)->state('random')->create()->map->freshFromUuid();
        $work_order_number = $jobs->random()->work_order_number;

        $json = $this->json('get', route('jobs.index', [
                'filter[work_order_number]' => $work_order_number,
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $expectedJobs = $jobs->where('work_order_number', $work_order_number)->take(config('domain.jobs_per_page'));
        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertCount($expectedJobs->count(), $jsonJobs);
        $this->assertNotEmpty($jsonJobs);
        $this->assertEquals(
            $expectedJobs->pluck('id')->toArray(),
            $jsonJobs->pluck('id')->toArray()
        );
    }

    /** @test */
    public function it_filters_jobs_by_partial_work_order_number_at_the_beginning()
    {
        $this->signInAdmin();

        factory(Job::class, [], 20)->state('random')->create();
        $partial_work_order_number = 'WO1';
        $work_order_number = "{$partial_work_order_number}_suffix";
        $expectedJobs = r_collect([
            create(Job::class, ['work_order_number' => $work_order_number])->freshFromUuid(),
        ]);

        $json = $this->json('get', route('jobs.index', [
                'filter[work_order_number]' => $partial_work_order_number,
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertCount($expectedJobs->count(), $jsonJobs);
        $this->assertNotEmpty($jsonJobs);
        $this->assertEquals(
            $expectedJobs->pluck('id')->toArray(),
            $jsonJobs->pluck('id')->toArray()
        );
    }

    /** @test */
    public function it_filters_jobs_by_partial_work_order_number_in_the_middle()
    {
        $this->signInAdmin();

        factory(Job::class, [], 20)->state('random')->create();
        $partial_work_order_number = 'WO1';
        $work_order_number = "prefix_{$partial_work_order_number}_suffix";
        $expectedJobs = r_collect([
            create(Job::class, ['work_order_number' => $work_order_number])->freshFromUuid(),
        ]);

        $json = $this->json('get', route('jobs.index', [
                'filter[work_order_number]' => $partial_work_order_number,
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertCount($expectedJobs->count(), $jsonJobs);
        $this->assertNotEmpty($jsonJobs);
        $this->assertEquals(
            $expectedJobs->pluck('id')->toArray(),
            $jsonJobs->pluck('id')->toArray()
        );
    }

    /** @test */
    public function it_filters_jobs_by_partial_work_order_number_at_the_end()
    {
        $this->signInAdmin();

        factory(Job::class, [], 20)->state('random')->create();
        $partial_work_order_number = 'WO1';
        $work_order_number = "prefix_{$partial_work_order_number}";
        $expectedJobs = r_collect([
            create(Job::class, ['work_order_number' => $work_order_number])->freshFromUuid(),
        ]);

        $json = $this->json('get', route('jobs.index', [
                'filter[work_order_number]' => $partial_work_order_number,
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertCount($expectedJobs->count(), $jsonJobs);
        $this->assertNotEmpty($jsonJobs);
        $this->assertEquals(
            $expectedJobs->pluck('id')->toArray(),
            $jsonJobs->pluck('id')->toArray()
        );
    }

    /** @test */
    public function it_filters_jobs_by_full_control_number()
    {
        $this->signInAdmin();
        $this->withoutExceptionHandling();

        $control_number = 'CTRL1NUMBER';
        $expectedJobs = r_collect([
            create(Job::class, ['control_number_1' => $control_number])->freshFromUuid(),
            create(Job::class, ['control_number_2' => $control_number])->freshFromUuid(),
            create(Job::class, ['control_number_3' => $control_number])->freshFromUuid(),
            create(Job::class, ['control_number_4' => $control_number])->freshFromUuid(),
        ]);
        factory(Job::class, [], 20)->state('random')->create()->map->freshFromUuid();

        $control_number = Str::limit($control_number, ceil(strlen($control_number) / 2), '');
        $json = $this->json('get', route('jobs.index', [
                'filter[control_number]' => $control_number,
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertCount($expectedJobs->count(), $jsonJobs);
        $this->assertNotEmpty($jsonJobs);
        $this->assertEquals(
            $expectedJobs->pluck('id')->toArray(),
            $jsonJobs->pluck('id')->toArray()
        );
    }

    /** @test */
    public function it_filters_jobs_by_partial_control_number_at_the_beginning()
    {
        $this->signInAdmin();

        $partial_control_number = 'CTRL1NUMBER';
        $control_number = "{$partial_control_number}_suffix";
        $expectedJobs = r_collect([
            create(Job::class, ['control_number_1' => $control_number])->freshFromUuid(),
            create(Job::class, ['control_number_2' => $control_number])->freshFromUuid(),
            create(Job::class, ['control_number_3' => $control_number])->freshFromUuid(),
            create(Job::class, ['control_number_4' => $control_number])->freshFromUuid(),
        ]);

        factory(Job::class, [], 20)->state('random')->create()->map->freshFromUuid();

        $json = $this->json('get', route('jobs.index', [
                'filter[control_number]' => $partial_control_number,
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertCount($expectedJobs->count(), $jsonJobs);
        $this->assertNotEmpty($jsonJobs);
        $this->assertEquals(
            $expectedJobs->pluck('id')->toArray(),
            $jsonJobs->pluck('id')->toArray()
        );
    }

    /** @test */
    public function it_filters_jobs_by_partial_control_number_in_the_middle()
    {
        $this->signInAdmin();

        $partial_control_number = 'CTRL1NUMBER';
        $control_number = "prefix_{$partial_control_number}_suffix";
        $expectedJobs = r_collect([
            create(Job::class, ['control_number_1' => $control_number])->freshFromUuid(),
            create(Job::class, ['control_number_2' => $control_number])->freshFromUuid(),
            create(Job::class, ['control_number_3' => $control_number])->freshFromUuid(),
            create(Job::class, ['control_number_4' => $control_number])->freshFromUuid(),
        ]);

        factory(Job::class, [], 20)->state('random')->create()->map->freshFromUuid();

        $json = $this->json('get', route('jobs.index', [
                'filter[control_number]' => $partial_control_number,
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertCount($expectedJobs->count(), $jsonJobs);
        $this->assertNotEmpty($jsonJobs);
        $this->assertEquals(
            $expectedJobs->pluck('id')->toArray(),
            $jsonJobs->pluck('id')->toArray()
        );
    }

    /** @test */
    public function it_filters_jobs_by_partial_control_number_at_the_end()
    {
        $this->signInAdmin();

        $partial_control_number = 'CTRL1NUMBER';
        $control_number = "prefix_{$partial_control_number}";
        $expectedJobs = r_collect([
            create(Job::class, ['control_number_1' => $control_number])->freshFromUuid(),
            create(Job::class, ['control_number_2' => $control_number])->freshFromUuid(),
            create(Job::class, ['control_number_3' => $control_number])->freshFromUuid(),
            create(Job::class, ['control_number_4' => $control_number])->freshFromUuid(),
        ]);

        factory(Job::class, [], 20)->state('random')->create()->map->freshFromUuid();

        $json = $this->json('get', route('jobs.index', [
                'filter[control_number]' => $partial_control_number,
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertCount($expectedJobs->count(), $jsonJobs);
        $this->assertNotEmpty($jsonJobs);
        $this->assertEquals(
            $expectedJobs->pluck('id')->toArray(),
            $jsonJobs->pluck('id')->toArray()
        );
    }

    /** @test */
    public function it_filters_jobs_by_all_filters()
    {
        $this->signInAdmin();

        $jobs = factory(Job::class, [], 20)->state('random')->create()->map->freshFromUuid();
        $filterJob = $jobs->random();
        $filterJob->machine_id = create(Machine::class)->id;
        $filterJob->save();

        $json = $this->json('get', route('jobs.index', [
                'filter[machine_id]' => $machine_id = $filterJob->machine_id,
                'filter[wip_status]' => $wip_status = $filterJob->wip_status,
                'filter[work_order_number]' => $work_order_number = $filterJob->work_order_number,
                'filter[control_number]' => $control_number = $filterJob->control_numbers->random(),
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $expectedJobs = $jobs->where('wip_status', $wip_status)
            ->where('machine_id', $machine_id)
            ->where('work_order_number', $work_order_number)
            ->filter(function ($job) use ($control_number) {
                return $job->control_numbers->contains($control_number);
            })
            ->take(config('domain.jobs_per_page'));
        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertCount($expectedJobs->count(), $jsonJobs);
        $this->assertNotEmpty($jsonJobs);
        $this->assertEquals(
            $expectedJobs->pluck('id')->toArray(),
            $jsonJobs->pluck('id')->toArray()
        );
    }

    /** @test */
    public function it_sorts_jobs_by_due_date_asc_by_default()
    {
        $this->signInAdmin();

        $dates = $this->dueAtDates();
        $dates->shuffle()->each(function ($date) {
            create(Job::class, ['due_at' => $date]);
        });

        $json = $this->json('get', route('jobs.index', []))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertEquals(
            $dates->sort()->map->format('Y-m-d H:i:s')->values()->toArray(),
            $jsonJobs->pluck('due_at')->toArray()
        );
    }

    /** @test */
    public function it_sorts_jobs_by_work_order_number_asc()
    {
        $this->signInAdmin();

        $expectedJobs = factory(Job::class, 10)->state('random')->create()
            ->sortBy('work_order_number')->take(10)
            ->map->freshFromUuid()->values();

        $json = $this->json('get', route('jobs.index', [
                'sort' => 'work_order_number',
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertEquals(
            $expectedJobs->pluck('work_order_number')->toArray(),
            $jsonJobs->pluck('work_order_number')->toArray()
        );
    }

    /** @test */
    public function it_sorts_jobs_by_work_order_number_desc()
    {
        $this->signInAdmin();

        $expectedJobs = factory(Job::class, 10)->state('random')->create()
            ->sortByDesc('work_order_number')->take(10)
            ->map->freshFromUuid()->values();

        $json = $this->json('get', route('jobs.index', [
                'sort' => '-work_order_number',
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertEquals(
            $expectedJobs->pluck('work_order_number')->toArray(),
            $jsonJobs->pluck('work_order_number')->toArray()
        );
    }

    /** @test */
    public function it_sorts_jobs_by_customer_name_asc()
    {
        $this->signInAdmin();

        $expectedJobs = factory(Job::class, 10)->state('random')->create()
            ->sortBy('customer_name')->take(10)
            ->map->freshFromUuid()->values();

        $json = $this->json('get', route('jobs.index', [
                'sort' => 'customer_name',
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertEquals(
            $expectedJobs->pluck('customer_name')->toArray(),
            $jsonJobs->pluck('customer_name')->toArray()
        );
    }

    /** @test */
    public function it_sorts_jobs_by_customer_name_desc()
    {
        $this->signInAdmin();

        $expectedJobs = factory(Job::class, 10)->state('random')->create()
            ->sortByDesc('customer_name')->take(10)
            ->map->freshFromUuid()->values();

        $json = $this->json('get', route('jobs.index', [
                'sort' => '-customer_name',
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertEquals(
            $expectedJobs->pluck('customer_name')->toArray(),
            $jsonJobs->pluck('customer_name')->toArray()
        );
    }

    /** @test */
    public function it_sorts_jobs_by_due_date_asc()
    {
        $this->signInAdmin();

        $dates = $this->dueAtDates();
        $dates->shuffle()->each(function ($date) {
            create(Job::class, ['due_at' => $date]);
        });

        $json = $this->json('get', route('jobs.index', [
                'sort' => 'due_at',
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertEquals(
            $dates->sort()->map->format('Y-m-d H:i:s')->values()->toArray(),
            $jsonJobs->pluck('due_at')->toArray()
        );
    }

    /** @test */
    public function it_sorts_jobs_by_due_date_desc()
    {
        $this->signInAdmin();

        $dates = $this->dueAtDates();
        $dates->shuffle()->each(function ($date) {
            create(Job::class, ['due_at' => $date]);
        });

        $json = $this->json('get', route('jobs.index', [
                'sort' => '-due_at',
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertEquals(
            $dates->sort()->reverse()->map->format('Y-m-d H:i:s')->values()->toArray(),
            $jsonJobs->pluck('due_at')->toArray()
        );
    }

    /** @test */
    public function it_sorts_jobs_by_flag_asc()
    {
        $this->signInAdmin();

        $tuesday = create(Job::class, ['flag' => JobFlag::TUESDAY])->freshFromUuid();
        $monday = create(Job::class, ['flag' => JobFlag::MONDAY])->freshFromUuid();
        $wednesday = create(Job::class, ['flag' => JobFlag::WEDNESDAY])->freshFromUuid();
        $saturday = create(Job::class, ['flag' => JobFlag::SATURDAY])->freshFromUuid();
        $expected = [
            $monday->id,
            $tuesday->id,
            $wednesday->id,
            $saturday->id,
        ];

        $json = $this->json('get', route('jobs.index', [
                'sort' => 'flag',
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');

        $this->assertEquals(
            $expected,
            $jsonJobs->pluck('id')->toArray()
        );
    }

    /** @test */
    public function it_sorts_jobs_by_flag_desc()
    {
        $this->signInAdmin();

        $tuesday = create(Job::class, ['flag' => JobFlag::TUESDAY])->freshFromUuid();
        $monday = create(Job::class, ['flag' => JobFlag::MONDAY])->freshFromUuid();
        $wednesday = create(Job::class, ['flag' => JobFlag::WEDNESDAY])->freshFromUuid();
        $saturday = create(Job::class, ['flag' => JobFlag::SATURDAY])->freshFromUuid();
        $expected = [
            $saturday->id,
            $wednesday->id,
            $tuesday->id,
            $monday->id,
        ];

        $json = $this->json('get', route('jobs.index', [
                'sort' => '-flag',
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');

        $this->assertEquals(
            $expected,
            $jsonJobs->pluck('id')->toArray()
        );
    }

    /** @test */
    public function it_sorts_jobs_by_machine_id_asc()
    {
        $this->signInAdmin();

        $expectedJobs = factory(Job::class, 10)->state('random')->create()
            ->sortBy('machine_id')->take(10)
            ->map->freshFromUuid()->values();

        $json = $this->json('get', route('jobs.index', [
                'sort' => 'machine_id',
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertEquals(
            $expectedJobs->pluck('machine_id')->toArray(),
            $jsonJobs->pluck('machine_id')->toArray()
        );
    }

    /** @test */
    public function it_sorts_jobs_by_machine_id_desc()
    {
        $this->signInAdmin();

        $expectedJobs = factory(Job::class, 10)->state('random')->create()
            ->sortByDesc('machine_id')->take(10)
            ->map->freshFromUuid()->values();

        $json = $this->json('get', route('jobs.index', [
                'sort' => '-machine_id',
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertEquals(
            $expectedJobs->pluck('machine_id')->toArray(),
            $jsonJobs->pluck('machine_id')->toArray()
        );
    }

    /** @test */
    public function it_sorts_jobs_by_priority_asc()
    {
        $this->signInAdmin();

        $expectedJobs = factory(Job::class, 10)->state('random')->create()
            ->sortBy('priority')->take(10)
            ->map->freshFromUuid()->values();

        $json = $this->json('get', route('jobs.index', [
                'sort' => 'priority',
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertEquals(
            $expectedJobs->pluck('priority')->toArray(),
            $jsonJobs->pluck('priority')->toArray()
        );
    }

    /** @test */
    public function it_sorts_jobs_by_priority_desc()
    {
        $this->signInAdmin();

        $expectedJobs = factory(Job::class, 10)->state('random')->create()
            ->sortByDesc('priority')->take(10)
            ->map->freshFromUuid()->values();

        $json = $this->json('get', route('jobs.index', [
                'sort' => '-priority',
            ]))
            ->assertStatus(200)
            ->assertJsonStructure($this->expectedIndexJsonStructure())
            ->decodeResponseJson();

        $jsonJobs = r_collect($json)->get('data')->get('items')->get('data');
        $this->assertEquals(
            $expectedJobs->pluck('priority')->toArray(),
            $jsonJobs->pluck('priority')->toArray()
        );
    }

    /** @test */
    public function it_allows_admins_to_create_a_setup_card()
    {
        $this->signInAdmin();

        $job = collect(factory(Job::class)->state('specialty')->make([
            'type' => JobType::SETUP,
        ]))->filter();

        $this->from(route('jobs.index'))
            ->json('post', route('jobs.store'), $job->toArray())
            ->assertJsonStructure($this->expectedFormResponseJsonStructure());

        $this->assertDatabaseHas('jobs', $job->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_allows_admins_to_create_a_multi_hit_card_card()
    {
        $this->signInAdmin();

        $job = collect(factory(Job::class)->state('specialty')->make([
            'type' => JobType::MULTI_HIT,
        ]))->filter();

        $this->from(route('jobs.index'))
            ->json('post', route('jobs.store'), $job->toArray())
            ->assertJsonStructure($this->expectedFormResponseJsonStructure());

        $this->assertDatabaseHas('jobs', $job->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_allows_admins_to_create_a_sample_card()
    {
        $this->signInAdmin();

        $job = collect(factory(Job::class)->state('specialty')->make([
            'type' => JobType::SAMPLE,
        ]))->filter();

        $this->from(route('jobs.index'))
            ->json('post', route('jobs.store'), $job->toArray())
            ->assertJsonStructure($this->expectedFormResponseJsonStructure());

        $this->assertDatabaseHas('jobs', $job->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_allows_developers_to_create_a_setup_card()
    {
        $this->signInDeveloper();

        $job = collect(factory(Job::class)->state('specialty')->make([
            'type' => JobType::SETUP,
        ]))->filter();

        $this->from(route('jobs.index'))
            ->json('post', route('jobs.store'), $job->toArray())
            ->assertJsonStructure($this->expectedFormResponseJsonStructure());

        $this->assertDatabaseHas('jobs', $job->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_allows_developers_to_create_a_multi_hit_card_card()
    {
        $this->signInDeveloper();

        $job = collect(factory(Job::class)->state('specialty')->make([
            'type' => JobType::MULTI_HIT,
        ]))->filter();

        $this->from(route('jobs.index'))
            ->json('post', route('jobs.store'), $job->toArray())
            ->assertJsonStructure($this->expectedFormResponseJsonStructure());

        $this->assertDatabaseHas('jobs', $job->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_allows_developers_to_create_a_sample_card()
    {
        $this->signInDeveloper();

        $job = collect(factory(Job::class)->state('specialty')->make([
            'type' => JobType::SAMPLE,
        ]))->filter();

        $this->from(route('jobs.index'))
            ->json('post', route('jobs.store'), $job->toArray())
            ->assertJsonStructure($this->expectedFormResponseJsonStructure());

        $this->assertDatabaseHas('jobs', $job->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_does_not_allow_regular_users_to_create_a_setup_card()
    {
        $this->signIn();

        $job = collect(factory(Job::class)->state('specialty')->make([
            'type' => JobType::SETUP,
        ]))->filter();

        $this->from(route('jobs.index'))
            ->json('post', route('jobs.store'), $job->toArray())
            ->assertStatus(403);

        $this->assertDatabaseMissing('jobs', $job->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_does_not_allow_regular_users_to_create_a_multi_hit_card_card()
    {
        $this->signIn();

        $job = collect(factory(Job::class)->state('specialty')->make([
            'type' => JobType::MULTI_HIT,
        ]))->filter();

        $this->from(route('jobs.index'))
            ->json('post', route('jobs.store'), $job->toArray())
            ->assertStatus(403);

        $this->assertDatabaseMissing('jobs', $job->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_does_not_allow_regular_users_to_create_a_sample_card()
    {
        $this->signIn();

        $job = collect(factory(Job::class)->state('specialty')->make([
            'type' => JobType::SAMPLE,
        ]))->filter();

        $this->from(route('jobs.index'))
            ->json('post', route('jobs.store'), $job->toArray())
            ->assertStatus(403);

        $this->assertDatabaseMissing('jobs', $job->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_does_not_allow_guests_to_create_a_setup_card()
    {
        $job = collect(factory(Job::class)->state('specialty')->make([
            'type' => JobType::SETUP,
        ]))->filter();

        $this->from(route('jobs.index'))
            ->json('post', route('jobs.store'), $job->toArray())
            ->assertStatus(401);

        $this->assertDatabaseMissing('jobs', $job->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_does_not_allow_guests_to_create_a_multi_hit_card_card()
    {
        $job = collect(factory(Job::class)->state('specialty')->make([
            'type' => JobType::MULTI_HIT,
        ]))->filter();

        $this->from(route('jobs.index'))
            ->json('post', route('jobs.store'), $job->toArray())
            ->assertStatus(401);

        $this->assertDatabaseMissing('jobs', $job->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_does_not_allow_guests_to_create_a_sample_card()
    {
        $job = collect(factory(Job::class)->state('specialty')->make([
            'type' => JobType::SAMPLE,
        ]))->filter();

        $this->from(route('jobs.index'))
            ->json('post', route('jobs.store'), $job->toArray())
            ->assertStatus(401);

        $this->assertDatabaseMissing('jobs', $job->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_does_not_allow_admins_to_create_a_regular_job()
    {
        $this->signInAdmin();

        $job = collect(factory(Job::class)->state('only-updatable')->make())->filter();

        $this->from(route('jobs.index'))
            ->json('post', route('jobs.store'), $job->toArray())
            ->assertStatus(403);

        $this->assertDatabaseMissing('jobs', $job->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_does_not_allow_developers_to_create_a_regular_job()
    {
        $this->signInDeveloper();

        $job = collect(factory(Job::class)->state('only-updatable')->make())->filter();

        $this->from(route('jobs.index'))
            ->json('post', route('jobs.store'), $job->toArray())
            ->assertStatus(403);

        $this->assertDatabaseMissing('jobs', $job->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_does_not_allow_general_users_to_create_a_regular_job()
    {
        $this->signIn();

        $job = collect(factory(Job::class)->state('only-updatable')->make())->filter();

        $this->from(route('jobs.index'))
            ->json('post', route('jobs.store'), $job->toArray())
            ->assertStatus(403);

        $this->assertDatabaseMissing('jobs', $job->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_does_not_allow_guests_to_create_a_regular_job()
    {
        $job = collect(factory(Job::class)->state('only-updatable')->make())->filter();

        $this->from(route('jobs.index'))
            ->json('post', route('jobs.store'), $job->toArray())
            ->assertStatus(401);

        $this->assertDatabaseMissing('jobs', $job->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_updates_a_job()
    {
        $this->signInAdmin();

        $job = factory(Job::class)->states('only-updatable')->create([
            'machine_id' => create(Machine::class)->id,
        ])->freshFromUuid();
        $original = $job->toArray();
        // collect($original)->only([
        //     'start_at', 'due_at'
        // ])->filter()->dd();
        $new = factory(Job::class)->states('alternate-only-updatable')->make([
            'machine_id' => create(Machine::class)->id,
        ])->toArray();

        $this->from(route('jobs.index'))
            ->json('put', route('jobs.update', ['job' => $job]), $new)
            ->assertJsonStructure($this->expectedFormResponseJsonStructure());

        $this->assertDatabaseMissing('jobs', collect($original)->except(
            $this->mergeWithJobAppends()
        )->toArray());
        $this->assertDatabaseHas('jobs', collect($new)->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_only_allows_updating_of_certain_fields()
    {
        $this->signInAdmin();

        $job = factory(Job::class)->create([
            'machine_id' => create(Machine::class)->id,
        ])->freshFromUuid();

        $new = collect(factory(Job::class)->state('alternate-only-updatable')->make([
            'machine_id' => create(Machine::class)->id,
        ])->toArray())->filter()->toArray();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), $new)
            ->assertJsonStructure($this->expectedFormResponseJsonStructure());

        $updatableFields = [
            'machine_id',
            'wip_status',
            'art_status',
            'priority',
            'pick_status',
            'notes',
            'type',
            'start_at',
            'due_at',
            'started_at',
            'completed_at',
            'sort_order',
            'flag',
        ];
        $this->assertDatabaseMissing('jobs', $job->only(array_merge(['id'], $updatableFields)));
        $this->assertDatabaseHas('jobs', collect(array_merge($job->toArray(), $new))->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_allows_admins_to_update_jobs()
    {
        $this->signInAdmin();

        $job = factory(Job::class)->states('only-updatable')->create([
            'machine_id' => create(Machine::class)->id,
        ])->freshFromUuid();
        $original = $job->toArray();
        $new = factory(Job::class)->states('alternate-only-updatable')->make([
            'machine_id' => create(Machine::class)->id,
        ])->toArray();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), $new)
            ->assertJsonStructure($this->expectedFormResponseJsonStructure());

        $this->assertDatabaseMissing('jobs', collect($original)->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
        $this->assertDatabaseHas('jobs', collect($new)->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_allows_developers_to_update_jobs()
    {
        $this->signInDeveloper();

        $job = factory(Job::class)->states('only-updatable')->create([
            'machine_id' => create(Machine::class)->id,
        ])->freshFromUuid();
        $original = $job->toArray();
        $new = factory(Job::class)->states('alternate-only-updatable')->make([
            'machine_id' => create(Machine::class)->id,
        ])->toArray();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), $new)
            ->assertJsonStructure($this->expectedFormResponseJsonStructure());

        $this->assertDatabaseMissing('jobs', collect($original)->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
        $this->assertDatabaseHas('jobs', collect($new)->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_does_not_allow_general_users_to_update_jobs()
    {
        $this->signIn();

        $job = factory(Job::class)->states('only-updatable')->create([
            'machine_id' => create(Machine::class)->id,
        ])->freshFromUuid();
        $original = $job->toArray();
        $new = factory(Job::class)->states('alternate-only-updatable')->make([
            'machine_id' => create(Machine::class)->id,
        ])->toArray();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), $new)
            ->assertStatus(403);

        $this->assertDatabaseHas('jobs', collect($original)->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
        $this->assertDatabaseMissing('jobs', collect($new)->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_does_not_allow_guests_to_update_jobs()
    {
        $job = factory(Job::class)->states('only-updatable')->create([
            'machine_id' => create(Machine::class)->id,
        ])->freshFromUuid();
        $original = $job->toArray();
        $new = factory(Job::class)->states('alternate-only-updatable')->make([
            'machine_id' => create(Machine::class)->id,
        ])->toArray();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), $new)
            ->assertStatus(401);

        $this->assertDatabaseHas('jobs', collect($original)->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
        $this->assertDatabaseMissing('jobs', collect($new)->except(
            $this->mergeWithJobAppends(['uuid'])
        )->toArray());
    }

    /** @test */
    public function it_requires_the_machine_id_to_exist()
    {
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'machine_id' => 120,
            ])
            ->assertJsonValidationErrors([
                'machine_id'
            ]);
    }

    /** @test */
    public function it_requires_the_machine_id_to_be_numeric()
    {
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'machine_id' => 'not-numeric',
            ])
            ->assertJsonValidationErrors([
                'machine_id'
            ]);
    }

    /** @test */
    public function it_requires_a_valid_wip_status()
    {
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'wip_status' => 'not_a_valid_wip_status',
            ])
            ->assertJsonValidationErrors([
                'wip_status'
            ]);
    }

    /** @test */
    public function it_requires_a_valid_art_status()
    {
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'art_status' => 'not_a_valid_art_status',
            ])
            ->assertJsonValidationErrors([
                'art_status'
            ]);
    }

    /** @test */
    public function it_requires_priority_to_be_numeric()
    {
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'priority' => 'not_numeric',
            ])
            ->assertJsonValidationErrors([
                'priority'
            ]);
    }

    /** @test */
    public function it_requires_a_valid_pick_status()
    {
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'pick_status' => 'not_a_valid_pick_status',
            ])
            ->assertJsonValidationErrors([
                'pick_status'
            ]);
    }

    /** @test */
    public function it_requires_a_valid_type()
    {
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'type' => 'not_a_valid_type',
            ])
            ->assertJsonValidationErrors([
                'type'
            ]);
    }

    /** @test */
    public function it_requires_start_at_to_be_a_date()
    {
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'start_at' => 'not_a_valid_start_at',
            ])
            ->assertJsonValidationErrors([
                'start_at'
            ]);
    }

    /** @test */
    public function it_requires_due_at_to_be_a_date()
    {
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'due_at' => 'not_a_valid_due_at',
            ])
            ->assertJsonValidationErrors([
                'due_at'
            ]);
    }

    /** @test */
    public function it_requires_started_at_to_be_a_date()
    {
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'started_at' => 'not_a_valid_started_at',
            ])
            ->assertJsonValidationErrors([
                'started_at'
            ]);
    }

    /** @test */
    public function it_requires_completed_at_to_be_a_date()
    {
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'completed_at' => 'not_a_valid_completed_at',
            ])
            ->assertJsonValidationErrors([
                'completed_at'
            ]);
    }

    /** @test */
    public function it_requires_the_sort_order_to_be_numeric()
    {
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'sort_order' => 'not-numeric',
            ])
            ->assertJsonValidationErrors([
                'sort_order'
            ]);
    }

    /** @test */
    public function it_requires_duration_to_be_numeric()
    {
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'duration' => 'not-numeric',
            ])
            ->assertJsonValidationErrors([
                'duration'
            ]);
    }

    /** @test */
    public function it_requires_duration_when_type_is_not_default()
    {
        $this->signInAdmin();

        $job = collect(factory(Job::class)->state('specialty')->make([
            'duration' => null,
        ])->toArray())->filter();

        $this->from(route('jobs.index'))
            ->json('post', route('jobs.store'), $job->toArray())
            ->assertJsonValidationErrors([
                'duration'
            ]);
    }

    /** @test */
    public function it_requires_print_detail_to_be_a_file()
    {
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'print_detail' => 'not_a_file',
            ])
            ->assertJsonValidationErrors([
                'print_detail'
            ]);
    }

    /** @test */
    public function it_requires_a_valid_flag()
    {
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'flag' => 'not_a_valid_flag',
            ])
            ->assertJsonValidationErrors([
                'flag'
            ]);
    }

    /** @test */
    public function it_requires_garment_ready_to_be_boolean()
    {
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'garment_ready' => 'not_a_boolean',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'garment_ready'
            ]);
    }

    /** @test */
    public function it_requires_screens_ready_to_be_boolean()
    {
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'screens_ready' => 'not_a_boolean',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'screens_ready'
            ]);
    }

    /** @test */
    public function it_requires_ink_ready_to_be_boolean()
    {
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'ink_ready' => 'not_a_boolean',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'ink_ready'
            ]);
    }

    /** @test */
    public function it_uploads_the_print_details()
    {
        Storage::fake('public');
        $user = $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();
        $file = UploadedFile::fake()->create('uploadedDetails.pdf');

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'print_detail' => $file,
            ])
            ->assertJsonStructure($this->expectedFormResponseJsonStructure());

        $media = $job->fresh()->print_detail;
        $directory = collect(Storage::disk('public')->allDirectories())->first();
        $this->assertNotNull($media);
        $this->assertEquals('uploadedDetails.pdf', $media->file_name);
        $this->assertTrue(Storage::disk('public')->exists("{$directory}/uploadedDetails.pdf"));
    }

    /** @test */
    public function it_replaces_the_print_details()
    {
        Storage::fake('public');
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();
        $file1 = UploadedFile::fake()->create('replacedDetails1.pdf');

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'print_detail' => $file1,
            ])
            ->assertJsonStructure($this->expectedFormResponseJsonStructure());

        $media = $job->fresh()->print_detail;
        $directory1 = collect(Storage::disk('public')->allDirectories())->first();
        $this->assertNotNull($media);
        $this->assertEquals('replacedDetails1.pdf', $media->file_name);
        $this->assertTrue(Storage::disk('public')->exists("{$directory1}/replacedDetails1.pdf"));

        $file2 = UploadedFile::fake()->create('details2.pdf');
        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'print_detail' => $file2,
            ])
            ->assertJsonStructure($this->expectedFormResponseJsonStructure());

        $media = $job->fresh()->print_detail;
        $directory2 = collect(Storage::disk('public')->allDirectories())->first();
        $this->assertNotNull($media);
        $this->assertEquals('details2.pdf', $media->file_name);
        $this->assertFalse(Storage::disk('public')->exists("{$directory1}/details.pdf"));
        $this->assertTrue(Storage::disk('public')->exists("{$directory2}/details2.pdf"));
    }

    /** @test */
    public function it_deletes_the_print_details()
    {
        Storage::fake('public');
        $this->signInAdmin();

        $job = create(Job::class)->freshFromUuid();
        $file = UploadedFile::fake()->create('details.pdf');
        $job->addMedia($file)->toMediaCollection('print_detail');
        $this->assertEquals('details.pdf', $job->print_detail->file_name);

        $directory = collect(Storage::disk('public')->allDirectories())->first();
        $this->assertTrue(Storage::disk('public')->exists("{$directory}/details.pdf"));

        $this->from(route('jobs.index'))
            ->json('patch', route('jobs.update', ['job' => $job]), [
                'print_detail' => null,
            ])
            ->assertJsonStructure($this->expectedFormResponseJsonStructure());

        $media = $job->fresh()->getFirstMedia('print_detail');
        $this->assertNull($media);
        $this->assertFalse(Storage::disk('public')->exists('1/details.pdf'));
    }

    protected function expectedFormResponseJsonStructure()
    {
        return [
            'data' => [
                'status',
                'model',
            ],
        ];
    }

    protected function expectedIndexJsonStructure()
    {
        return [
            'data' => [
                'items',
            ],
        ];
    }

    protected function dueAtDates()
    {
        return collect([
            now()->subDays(1),
            now(),
            now()->addDays(1),
            now()->addDays(2),
            now()->addDays(5),
        ]);
    }
}
