<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_allows_users_to_register()
    {
        $this->post('/register', $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'secret',
            'terms' => 'on',
            'usa_resident' => 'on'
        ])->assertStatus(302);

        $data = collect($data)->except(['password', 'terms', 'usa_resident']);
        $data->put('terms_accepted', true);
        $data->put('usa_resident', true);
        $this->assertDatabaseHas('users', $data->toArray());
    }

    /**
    * @test
    */
    public function it_requires_usa_resident_to_be_checked_to_register()
    {
        $this->post('/register', $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'secret',
            'terms' => 'on',
        ])->assertSessionHasErrors(['usa_resident']);

        $data = collect($data)->except(['password', 'terms', 'usa_resident']);
        $this->assertDatabaseMissing('users', $data->toArray());

        $this->post('/register', $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'secret',
            'terms' => 'on',
            'usa_resident' => 'off',
        ])->assertSessionHasErrors(['usa_resident']);

        $data = collect($data)->except(['password', 'terms', 'usa_resident']);
        $this->assertDatabaseMissing('users', $data->toArray());
    }

    /**
    * @test
    */
    public function it_requires_terms_to_be_checked_to_register()
    {
        $this->post('/register', $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'secret',
            'usa_resident' => 'on',
        ])->assertSessionHasErrors(['terms']);

        $data = collect($data)->except(['password', 'terms', 'usa_resident']);
        $this->assertDatabaseMissing('users', $data->toArray());

        $this->post('/register', $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'secret',
            'terms' => 'off',
            'usa_resident' => 'on',
        ])->assertSessionHasErrors(['terms']);

        $data = collect($data)->except(['password', 'terms', 'usa_resident']);
        $this->assertDatabaseMissing('users', $data->toArray());
    }
}
