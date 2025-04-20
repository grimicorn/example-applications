<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class APITokenGenerateControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_generates_token_for_a_user()
    {
        $user = factory(App\User::class)->create();

        Auth::login($user, true);

        $response = $this->put('/token/generate');

        Auth::logout();
        $this->assertEquals(302, $response->status());

        $this->assertEquals(session('api_token'), $user->fresh()->api_token);
    }
}
