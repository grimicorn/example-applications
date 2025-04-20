<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Support\ExchangeSpaceDealType;
use App\Support\ExchangeSpaceStatusType;
use Tests\Support\HasExchangeSpaceCreators;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class BuyerInquiryAcceptanceTest extends TestCase
{
    use RefreshDatabase, HasExchangeSpaceCreators;

    /**
    * @test
    */
    public function it_accepts_a_buyer_inquiry()
    {
        $this->withoutExceptionHandling();
        $seller = $this->signInWithEvents();
        $space = $this->createInitalInquiry([
            'resolved' => false,
        ], $seller);

        $this->post(route('buyer-inquiry.acceptance.store', ['id' => $space->id]))
             ->assertStatus(302)
             ->assertSessionHas('status')
             ->assertSessionHas('success', true)
             ->assertRedirect(
                route('exchange-spaces.conversations.show', [
                    'id' => $space->id,
                    'c_id' => $space->conversations()->where('is_inquiry', 1)->first()->id,
                ])
             );

        $space = $space->fresh();
        $this->assertEquals(ExchangeSpaceStatusType::ACCEPTED, $space->status);
        $this->assertEquals(ExchangeSpaceDealType::PRE_NDA, $space->deal);
        $this->assertTrue($space->conversations->fresh()->first()->resolved);
    }

    /**
    * @test
    */
    public function it_rejects_a_buyer_inquiry()
    {
        $this->withoutExceptionHandling();
        Mail::fake();

        $this->signInWithEvents();
        $inquiry = factory('App\ExchangeSpace')->states('current_user', 'inquiry')->create();
        $seller = factory('App\ExchangeSpaceMember')->states('seller')->create([
            'exchange_space_id' => $inquiry->id,
            'user_id' => $inquiry->user->id,
        ]);
        $buyer = factory('App\ExchangeSpaceMember')->states('buyer')->create(['exchange_space_id' => $inquiry->id]);

        // Reject the inquiry
        $response = $this->delete(
            route('business-inquiry.acceptance.destroy', ['id' => $inquiry->id]),
            $data = [
                'reason' => 'Some reason',
                'explanation' => 'Some explanation',
            ]
        );

        // Check the response.
        $response->assertStatus(302)
                 ->assertSessionHas('status')
                 ->assertSessionHas('success', true);

        // Check the inquiry
        $inquiry = $inquiry->fresh();
        $this->assertEquals(ExchangeSpaceStatusType::REJECTED, $inquiry->status);

        // Make sure the reason was recorded.
        $data['exchange_space_id'] = $inquiry->id;
        $this->assertDatabaseHas('rejection_reasons', $data);
    }

    /**
    * @test
    */
    public function it_only_allows_sellers_to_accept_inquiry()
    {
        $this->signInWithEvents();
        $inquiry = $this->createInitalInquiry([
            'resolved' => false,
        ]);

        $buyer = $inquiry->buyer_user;
        $seller = $inquiry->seller_user;

        // Sign in the buyer to simulate a non-seller trying to access the inquiry
        $this->signInWithEvents($buyer);
        $response = $this->post(route('buyer-inquiry.acceptance.store', ['id' => $inquiry->id]));
        $response->assertStatus(403);
        $inquiry = $inquiry->fresh();
        $this->assertEquals(ExchangeSpaceStatusType::INQUIRY, $inquiry->status);

        // Sign in the seller accept the inquiry
        $this->signInWithEvents($seller);
        $response = $this->post(route('buyer-inquiry.acceptance.store', ['id' => $inquiry->id]));
        $response->assertStatus(302);
        $inquiry = $inquiry->fresh();
        $this->assertEquals(ExchangeSpaceStatusType::ACCEPTED, $inquiry->status);
    }
}
