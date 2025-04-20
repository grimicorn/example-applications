<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class UserExampleSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    * @group failing
    */
    public function it_seeds_the_database_with_example_users()
    {
        $this->seed(\UserExampleSeeder::class);

        $this->assertCount(4, \App\User::all());
    }
}
