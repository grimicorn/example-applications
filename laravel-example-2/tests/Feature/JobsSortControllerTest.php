<?php

namespace Tests\Feature;

use App\Job;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JobsSortControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sorts_multiple_jobs()
    {
        $this->signInAdmin();

        $job1 = create(Job::class, ['sort_order' => 1,])->freshFromUuid();
        $job2 = create(Job::class, ['sort_order' => 2,])->freshFromUuid();
        $job3 = create(Job::class, ['sort_order' => 3,])->freshFromUuid();
        $job4 = create(Job::class, ['sort_order' => 4,])->freshFromUuid();

        $this->json('post', route('jobs-sort.store'), [
            'jobs' => [
                ['id' => $job1->id, 'sort_order' => 2],
                ['id' => $job2->id, 'sort_order' => 1],
                ['id' => $job4->id, 'sort_order' => 3],
            ],
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['status'],
            ]);

        $this->assertEquals(2, $job1->fresh()->sort_order);
        $this->assertEquals(1, $job2->fresh()->sort_order);
        $this->assertEquals(3, $job3->fresh()->sort_order);
        $this->assertEquals(3, $job4->fresh()->sort_order);
    }

    /** @test */
    public function it_allows_developers_to_sort_jobs()
    {
        $this->signInDeveloper();

        $job1 = create(Job::class, ['sort_order' => 1,])->freshFromUuid();
        $job2 = create(Job::class, ['sort_order' => 2,])->freshFromUuid();
        $job3 = create(Job::class, ['sort_order' => 3,])->freshFromUuid();
        $job4 = create(Job::class, ['sort_order' => 4,])->freshFromUuid();

        $this->json('post', route('jobs-sort.store'), [
            'jobs' => [
                ['id' => $job1->id, 'sort_order' => 2],
                ['id' => $job2->id, 'sort_order' => 1],
                ['id' => $job4->id, 'sort_order' => 3],
            ],
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['status'],
            ]);

        $this->assertEquals(2, $job1->fresh()->sort_order);
        $this->assertEquals(1, $job2->fresh()->sort_order);
        $this->assertEquals(3, $job3->fresh()->sort_order);
        $this->assertEquals(3, $job4->fresh()->sort_order);
    }

    /** @test */
    public function it_allows_admins_to_sort_jobs()
    {
        $this->signInAdmin();

        $job = create(Job::class, ['sort_order' => 1,])->freshFromUuid();

        $this->json('post', route('jobs-sort.store'), [
            'jobs' => [
                ['id' => $job->id, 'sort_order' => 2],
            ],
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['status'],
            ]);

        $this->assertEquals(2, $job->fresh()->sort_order);
    }

    /** @test */
    public function it_does_not_allow_general_users_to_sort_jobs()
    {
        $this->signIn();

        $job = create(Job::class, ['sort_order' => 1,])->freshFromUuid();

        $this->json('post', route('jobs-sort.store'), [
            'jobs' => [
                ['id' => $job->id, 'sort_order' => 2],
            ],
        ])
            ->assertStatus(403);

        $this->assertEquals(1, $job->fresh()->sort_order);
    }

    /** @test */
    public function it_does_not_allow_guests_to_sort_jobs()
    {
        $job = create(Job::class, ['sort_order' => 1,])->freshFromUuid();

        $this->json('post', route('jobs-sort.store'), [
            'jobs' => [
                ['id' => $job->id, 'sort_order' => 2],
            ],
        ])
            ->assertStatus(401);

        $this->assertEquals(1, $job->fresh()->sort_order);
    }

    /** @test */
    public function it_requires_all_jobs_to_have_a_job_id()
    {
        $this->signInAdmin();

        $job1 = create(Job::class, ['sort_order' => 1,])->freshFromUuid();

        $this->json('post', route('jobs-sort.store'), [
            'jobs' => [
                ['sort_order' => 2],
            ],
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('jobs.0.id');

        $this->assertEquals(1, $job1->fresh()->sort_order);
    }

    /** @test */
    public function it_requires_all_job_ids_to_be_numeric()
    {
        $this->signInAdmin();

        $job1 = create(Job::class, ['sort_order' => 1,])->freshFromUuid();

        $this->json('post', route('jobs-sort.store'), [
            'jobs' => [
                ['id' => 'not-numeric','sort_order' => 2],
            ],
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('jobs.0.id');

        $this->assertEquals(1, $job1->fresh()->sort_order);
    }

    /** @test */
    public function it_requires_all_job_ids_to_be_existing_jobs()
    {
        $this->signInAdmin();

        $job1 = create(Job::class, ['sort_order' => 1,])->freshFromUuid();

        $this->json('post', route('jobs-sort.store'), [
            'jobs' => [
                ['id' => 200,'sort_order' => 2],
            ],
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('jobs.0.id');

        $this->assertEquals(1, $job1->fresh()->sort_order);
    }

    /** @test */
    public function it_requires_all_jobs_to_have_a_sort_order()
    {
        $this->signInAdmin();

        $job1 = create(Job::class, ['sort_order' => 1,])->freshFromUuid();

        $this->json('post', route('jobs-sort.store'), [
            'jobs' => [
                ['id' => $job1->id],
            ],
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('jobs.0.sort_order');

        $this->assertEquals(1, $job1->fresh()->sort_order);
    }

    /** @test */
    public function it_requires_all_sort_order_to_be_numeric()
    {
        $this->signInAdmin();

        $job1 = create(Job::class, ['sort_order' => 1,])->freshFromUuid();

        $this->json('post', route('jobs-sort.store'), [
            'jobs' => [
                ['id' => $job1->id,'sort_order' => 'not-numeric'],
            ],
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('jobs.0.sort_order');

        $this->assertEquals(1, $job1->fresh()->sort_order);
    }

    /** @test */
    public function it_requires_all_sort_order_to_be_a_positive_number()
    {
        $this->signInAdmin();

        $job1 = create(Job::class, ['sort_order' => 1,])->freshFromUuid();

        $this->json('post', route('jobs-sort.store'), [
            'jobs' => [
                ['id' => $job1->id,'sort_order' => -1],
            ],
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('jobs.0.sort_order');

        $this->assertEquals(1, $job1->fresh()->sort_order);
    }
}
