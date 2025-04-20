<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_creates_a_user_as_a_stripe_customer_on_registration()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/register', [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName,
            'email' => $email = $this->faker->email,
            'password' => 'secret',
            'terms' => 'on',
            'usa_resident' => 'on',
        ]);

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user->stripe_id);
    }
}
