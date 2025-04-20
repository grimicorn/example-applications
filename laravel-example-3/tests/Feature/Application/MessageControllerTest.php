<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use Tests\Support\HasExchangeSpaceCreators;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class MessageControllerTest extends TestCase
{
    use RefreshDatabase;
    use HasExchangeSpaceCreators;

    /**
    * @test
    */
    public function it_allows_admins_to_delete_a_message()
    {
        $user = $this->signInDeveloperWithEvents();
        $message = factory('App\Message')->create();

        $response = $this->json(
            'DELETE',
            route('conversation.message.destroy', ['id' => $message->id])
        );

        $message = $message->fresh();
        $response->assertStatus(200);
        $this->assertNotNull($message->deleted_by_id);
        $this->assertTrue($message->trashed());
    }

    /**
     * @test
     */
    public function it_allows_impersonating_admins_to_delete_a_message()
    {
        $this->withoutExceptionHandling();
        $user = $this->signInDeveloperWithEvents();
        $message = factory('App\Message')->create();

        // Setup impersonation (Who the user is does not really matter)
        $this->get('/spark/kiosk/users/impersonate/' . factory('App\User')->create()->id);

        $response = $this->json(
            'DELETE',
            route('conversation.message.destroy', ['id' => $message->id])
        );

        $response->assertStatus(200);
        $this->assertTrue($message->fresh()->trashed());
    }

    /**
    * @test
    */
    public function it_does_not_allow_non_admins_to_delete_a_message()
    {
        $user = $this->signInwithEvents();
        $message = factory('App\Message')->create();

        $response = $this->json(
            'DELETE',
            route('conversation.message.destroy', ['id' => $message->id])
        );

        $response->assertStatus(403);
        $this->assertFalse($message->fresh()->trashed());
    }
}
