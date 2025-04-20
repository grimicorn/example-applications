<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Support\HasExchangeSpaceCreators;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class BuyerInquiryDisplayWelcomeTest extends TestCase
{
    use HasExchangeSpaceCreators, RefreshDatabase;

    /**
    * @test
    * @group failing
    */
    public function it_sets_a_users_display_inquiry_intro_to_false()
    {
        $this->withoutExceptionHandling();

        // Sign in the user.
        $user = $this->signInWithEvents();

        // Create the conversation.
        $conversation = $this->createInquiryConversation([], $user);

        // Make sure the default state is false.
        $this->assertTrue($user->fresh()->display_inquiry_intro);

        // Disable displaying the inquiry intro.
        $response = $this->delete(route('business-inquiry.display-intro.destroy'));

        // Make sure everything went ok.
        $response->assertStatus(302);
        $this->assertFalse($user->fresh()->display_inquiry_intro);
    }
}
