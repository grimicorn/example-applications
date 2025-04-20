<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use App\ExchangeSpace;
use Illuminate\Support\Facades\Event;
use App\Support\ExchangeSpaceStatusType;
use App\Support\ExchangeSpace\MemberRole;
use App\Support\ConversationCategoryType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\ExchangeSpaceMember;

// @codingStandardsIgnoreStart
class BuyerInquiryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_creates_a_new_buyer_inquiry()
    {
        Event::fake();

        $buyer = $this->signInWithEvents();
        $listing = factory('App\Listing')->create();

        $response = $this->post(route('business-inquiry.store'), [
            'listing_id' => $listing->id,
            'body' => $body = 'Contact message',
        ]);

        $response->assertStatus(302);

        // Exchange space should be created with the inquiry status, the correct listing id, correct business name and the correct exchnge space owner
        $inquiry = ExchangeSpace::latest()->first();
        $sellerLastName = $inquiry->sellerUser()->last_name;
        $buyerLastName = $inquiry->buyerUser()->last_name;
        $this->assertNotNull($inquiry);
        $this->assertEquals(ExchangeSpaceStatusType::INQUIRY, $inquiry->status);
        $this->assertEquals($listing->id, $inquiry->listing_id);
        $this->assertEquals("{$sellerLastName}/{$buyerLastName}", $inquiry->title);
        $this->assertEquals($listing->user_id, $inquiry->user_id);

        // The exchange space should have 2 members.
        $this->assertCount(2, $inquiry->members);

        // The current user should be a member with the role of buyer.
        $this->assertEquals(
            MemberRole::BUYER,
            $inquiry->members->where('user_id', $buyer->id)->first()->role
        );

        // The listing owner should be a member with the role of seller.
        $this->assertEquals(
            MemberRole::SELLER,
            $inquiry->members->where('user_id', $listing->user->id)->first()->role
        );

        // The conversation should be created.
        $this->assertCount(1, $inquiry->conversations);

        // The conversation should have one message title "Business Inquiry" and the category of business inquiry.
        $this->assertEquals('Business Inquiry', $inquiry->conversations->first()->title);
        $this->assertTrue($inquiry->conversations->first()->is_inquiry);
        $this->assertEquals(
            ConversationCategoryType::BUYER_INQUIRY,
            $inquiry->conversations->first()->category
        );

        // The conversation should have 1 message with the submitted body message. The buyer should be the message creator.
        $this->count(1, $inquiry->conversations->first()->messages);
        $this->assertNotNull($inquiry->conversations->first()->messages->first()->body);
        $this->assertEquals(
            $body,
            $inquiry->conversations->first()->messages->first()->body
        );
        $this->assertEquals(
            $buyer->id,
            $inquiry->conversations->first()->messages->first()->user_id
        );
    }

    /**
    * @test
    */
    public function it_does_not_allow_sellers_to_create_an_inquiry_with_their_own_listings()
    {
        $seller = $this->signInWithEvents();
        $listing = factory('App\Listing')->create([
            'user_id' => $seller->id,
        ]);

        $response = $this->post(route('business-inquiry.store'), [
            'listing_id' => $listing->id,
        ]);

        $response->assertStatus(302);

        $this->assertCount(0, $listing->spaces()->get());
    }

    /**
    * @test
    */
    public function it_does_not_allow_a_user_to_create_two_active_inquires_for_a_listing()
    {
        $buyer = $this->signInWithEvents();
        $listing = factory('App\Listing')->create();
        $this->assertCount(0, ExchangeSpaceMember::where('user_id', $buyer->id)->get());
        $this->assertCount(0, $listing->spaces);

        // Try to create an inital inquiry
        $this->json('POST', route('business-inquiry.store'), [
            'listing_id' => $listing->id,
            'body' => $this->faker->sentence,
        ])->assertStatus(200);
        $this->assertCount(1, ExchangeSpaceMember::where('user_id', $buyer->id)->get());
        $this->assertCount(1, $listing->fresh()->spaces);

        // Try to create an second inquiry for the same listing
        $this->json('POST', route('business-inquiry.store'), [
            'listing_id' => $listing->id,
            'body' => $this->faker->sentence,
        ])->assertStatus(422);
        $this->assertCount(1, ExchangeSpaceMember::where('user_id', $buyer->id)->get());
        $this->assertCount(1, $listing->fresh()->spaces);
    }

    /**
    * @test
    */
    public function it_allows_a_user_to_create_a_new_inquiry_once_rejected()
    {
        $buyer = $this->signInWithEvents();
        $listing = factory('App\Listing')->create();
        $this->assertCount(0, ExchangeSpaceMember::where('user_id', $buyer->id)->get());
        $this->assertCount(0, $listing->spaces);

        // Try to create an inital inquiry
        $this->json('POST', route('business-inquiry.store'), [
            'listing_id' => $listing->id,
            'body' => $this->faker->sentence,
        ])->assertStatus(200);
        $this->assertCount(1, ExchangeSpaceMember::where('user_id', $buyer->id)->get());
        $this->assertCount(1, $listing->fresh()->spaces);

        // Delete the space
        $listing->fresh()->current_user_active_space->delete();
        $this->assertNull($listing->fresh()->current_user_active_space);

        // Try to create an second inquiry for the same listing
        $this->json('POST', route('business-inquiry.store'), [
            'listing_id' => $listing->id,
            'body' => $this->faker->sentence,
        ])->assertStatus(200);
        $this->assertCount(2, ExchangeSpaceMember::where('user_id', $buyer->id)->get());
        $this->assertCount(2, $listing->fresh()->spaces()->withTrashed()->get());
        $this->assertNotNull($listing->fresh()->current_user_active_space);
    }
}
