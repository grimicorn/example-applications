<?php

namespace Tests\Feature\Application;

use App\User;
use App\Message;
use App\Listing;
use Carbon\Carbon;
use Tests\TestCase;
use App\Conversation;
use App\ExchangeSpace;
use App\ExchangeSpaceMember;
use Illuminate\Support\Facades\Event;
use App\Support\ExchangeSpace\MemberRole;
use Tests\Support\HasExchangeSpaceCreators;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class BuyerInquiryConversationsFilterTest extends TestCase
{
    use RefreshDatabase;
    use HasExchangeSpaceCreators;

    /**
    * @test
    */
    public function it_searches_buyer_inquiry_listing_business_name()
    {
        $this->withoutExceptionHandling();
        // Create/login a seller
        Event::fake();
        $seller = $this->signInWithEvents();

        // Create a few inquiry conversations.
        $conversations = [];
        for ($i=0; $i < 4; $i++) {
            $conversations[] = $this->createInquiryConversation([], $seller);
        }
        $conversations = collect($conversations);

        // Get a random business and set its name.
        $search = strtolower('Search value');
        $foundListing = $conversations->random()->space->listing;
        $foundListing->name_visible = true;
        $foundListing->business_name = title_case("before {$search} after");
        $foundListing->save();

        // Make the filter request.
        $response = $this->json('POST', route('business-inquiry.index'), [
            'search' => $search,
        ]);

        // Make sure we got the correct listings back
        $data = collect($response->json()['data']);

        $this->assertCount(1, $data);
        $this->assertEquals(
            $foundListing->id,
            $data->first()['space']['listing']['id']
        );
    }

    /**
    * @test
    */
    public function it_searches_buyer_inquiry_messages()
    {
        // Create/login a seller
        Event::fake();
        $seller = $this->signInWithEvents();

        // Create a few inquiry conversations.
        $conversations = [];
        for ($i=0; $i < $this->faker->numberBetween(3, 10); $i++) {
            $conversation = $this->createInquiryConversation([], $seller);
            $conversations[] = $conversation;

            // Add some messages to the conversation.
            for ($i=0; $i < $this->faker->numberBetween(3, 10); $i++) {
                $this->addMessageToConversation($conversation, []);
            }
        }
        $conversations = collect($conversations);

        // Get a random converstion and set its first message to contain the search value.
        $search = strtolower('Search value');
        $foundConversation = $conversations->random();
        $message = $foundConversation->fresh()->messages->first();
        $message->body = title_case("before {$search} after");
        $message->save();

        // Make the filter request.
        $response = $this->json('POST', route('business-inquiry.index'), [
            'search' => $search,
        ]);

        // Make sure we got the correct listings back
        $data = collect($response->json()['data']);
        $this->assertCount(1, $data);
        $this->assertEquals($foundConversation->id, $data->first()['id']);
    }

    /**
    * @test
    */
    public function it_searches_buyer_inquiry_creator_member_name()
    {
        // Create/login a seller
        Event::fake();
        $seller = $this->signInWithEvents();

        // Create a few inquiry conversations.
        $conversations = [];
        for ($i=0; $i < 4; $i++) {
            $conversations[] = $this->createInquiryConversation([], $seller);
        }
        $conversations = collect($conversations);

        // Get a random business and set its name.
        $search = strtolower('Search value');
        $foundCreator = $conversations->random()->space->buyerUser();
        $foundCreator->first_name = $search;
        $foundCreator->save();

        // Make the filter request.
        $response = $this->json('POST', route('business-inquiry.index'), [
            'search' => $search,
        ]);

        // Make sure we got the correct listings back
        $data = collect($response->json()['data']);
        $this->assertCount(1, $data);
        $this->assertEquals(
            $foundCreator->id,
            ExchangeSpace::find($data->first()['space']['id'])->buyerUser()->id
        );
    }

    /**
    * @test
    */
    public function it_searches_buyer_inquiry_creator_member_email()
    {
        // Create/login a seller
        Event::fake();
        $seller = $this->signInWithEvents();

        // Create a few inquiry conversations.
        $conversations = [];
        for ($i=0; $i < 4; $i++) {
            $conversations[] = $this->createInquiryConversation([], $seller);
        }
        $conversations = collect($conversations);

        // Get a random business and set its name.
        $search = str_slug('Search value');
        $foundCreator = $conversations->random()->space->buyerUser();
        $foundCreator->email = "{$search}@test.com";
        $foundCreator->save();

        // Make the filter request.
        $response = $this->json('POST', route('business-inquiry.index'), [
            'search' => $search,
        ]);

        // Make sure we got the correct listings back
        $data = collect($response->json()['data']);
        $this->assertCount(1, $data);
        $this->assertEquals(
            $foundCreator->id,
            ExchangeSpace::find($data->first()['space']['id'])->buyerUser()->id
        );
    }

    /**
    * @test
    */
    public function it_sorts_buyer_inquires_by_newest()
    {
        // Create/login a seller
        Event::fake();
        $seller = $this->signInWithEvents();

        // Create a few inquiry conversations.
        $conversations = [];
        for ($i=0; $i < 4; $i++) {
            $conversations[] = $this->createInquiryConversation([], $seller);
        }
        $conversations = collect($conversations);

        // Make the filter request.
        $response = $this->json('POST', route('business-inquiry.index'), [
            'sortKey' => 'newest',
        ]);

        // Make sure we got the correct listings back
        $data = collect($response->json()['data']);
        $this->assertCount($conversations->count(), $data);
        $this->assertEquals(
            $conversations->pluck('id')->reverse()->values(),
            $data->pluck('id')
        );
    }

    /**
    * @test
    */
    public function it_sorts_buyer_inquires_by_oldest()
    {
        // Create/login a seller
        Event::fake();
        $seller = $this->signInWithEvents();

        // Create a few inquiry conversations.
        $conversations = [];
        for ($i=0; $i < 4; $i++) {
            $conversations[] = $this->createInquiryConversation([], $seller);
        }
        $conversations = collect($conversations);

        // Make the filter request.
        $response = $this->json('POST', route('business-inquiry.index'), [
            'sortKey' => 'oldest',
        ]);

        // Make sure we got the correct listings back
        $data = collect($response->json()['data']);
        $this->assertCount($conversations->count(), $data);
        $this->assertEquals(
            $conversations->pluck('id')->values(),
            $data->pluck('id')
        );
    }

    /**
    * @test
    */
    public function it_sorts_buyer_inquires_by_listing_title_a_to_z()
    {
        // Create/login a seller
        Event::fake();
        $seller = $this->signInWithEvents();

        // Set some listing names.
        $listingNames = collect([
            'A Listing',
            'Fun Listing',
            'Some Other Listing',
        ]);
        $listingNamesShuffled = $listingNames->shuffle()->values();

        // Create a few inquiry conversations with the listing names set.
        $conversations = [];
        for ($i=0; $i < $listingNames->count(); $i++) {
            $conversation = $this->createInquiryConversation([], $seller);
            $conversations[] = $conversation;
            $listing = $conversation->space->listing;
            $listingName = $listingNamesShuffled->get($i);
            $listing->business_name = $listingName;
            $listing->save();
        }
        $conversations = collect($conversations);

        // Make the filter request.
        $response = $this->json('POST', route('business-inquiry.index'), [
            'sortKey' => 'listing_title_az',
        ]);

        // Make sure we got the correct listings back
        $data = r_collect($response->json()['data']);
        $this->assertCount($conversations->count(), $data);
        $this->assertEquals(
            $conversations->pluck('space')->pluck('listing')->sortBy('business_name')->pluck('id'),
            $data->pluck('space')->pluck('listing')->pluck('id')
        );
    }

    /**
    * @test
    */
    public function it_sorts_buyer_inquires_by_listing_title_z_to_a()
    {
        // Create/login a seller
        Event::fake();
        $seller = $this->signInWithEvents();

        // Set some listing names.
        $listingNames = collect([
            'A Listing',
            'Fun Listing',
            'Some Other Listing',
        ]);
        $listingNamesShuffled = $listingNames->shuffle()->values();

        // Create a few inquiry conversations with the listing names set.
        $conversations = [];
        for ($i=0; $i < $listingNames->count(); $i++) {
            $conversation = $this->createInquiryConversation([], $seller);
            $conversations[] = $conversation;
            $listing = $conversation->space->listing;
            $listingName = $listingNamesShuffled->get($i);
            $listing->business_name = $listingName;
            $listing->save();
        }
        $conversations = collect($conversations);

        // Make the filter request.
        $response = $this->json('POST', route('business-inquiry.index'), [
            'sortKey' => 'listing_title_za',
        ]);

        // Make sure we got the correct listings back
        $data = r_collect($response->json()['data']);
        $this->assertCount($conversations->count(), $data);
        $this->assertEquals(
            $conversations->pluck('space')->pluck('listing')->sortBy('business_name')->reverse()->pluck('id'),
            $data->pluck('space')->pluck('listing')->pluck('id')
        );
    }

    /**
    * @test
    */
    public function it_sorts_buyer_inquires_by_inquirer_name_a_to_z()
    {
        // Create/login a seller
        Event::fake();
        $seller = $this->signInWithEvents();

        // Create some buyers
        $buyers = [];
        $buyers[] = factory('App\User')->create(['first_name' => 'Angie']);
        $buyers[] = factory('App\User')->create(['first_name' => 'John']);
        $buyers[] = factory('App\User')->create(['first_name' => 'Rachel']);
        $buyers[] = factory('App\User')->create(['first_name' => 'Zack']);
        $buyers = collect($buyers);
        $buyersShuffled = collect($buyers)->shuffle()->values();

        // Create a few inquiry conversations.
        $conversations = [];
        for ($i=0; $i < $buyers->count(); $i++) {
            $conversations[] = $this->createInquiryConversation(
                [],
                $seller,
                $buyersShuffled->get($i)
            );
        }
        $conversations = collect($conversations);

        // Make the filter request.
        $response = $this->json('POST', route('business-inquiry.index'), [
            'sortKey' => 'inquirer_az',
        ]);

        // Make sure we got the correct listings back
        $data = r_collect($response->json()['data']);
        $this->assertCount($conversations->count(), $data);
        $this->assertEquals(
            $buyers->pluck('id')->values(),
            $data->map->get('space')->map->get('buyer_user_id')->values()
        );
    }

    /**
    * @test
    */
    public function it_sorts_buyer_inquires_by_inquirer_name_z_to_a()
    {
        // Create/login a seller
        Event::fake();
        $seller = $this->signInWithEvents();

        // Create some buyers
        $buyers = [];
        $buyers[] = factory('App\User')->create(['first_name' => 'Angie']);
        $buyers[] = factory('App\User')->create(['first_name' => 'John']);
        $buyers[] = factory('App\User')->create(['first_name' => 'Rachel']);
        $buyers[] = factory('App\User')->create(['first_name' => 'Zack']);
        $buyers = collect($buyers);
        $buyersShuffled = collect($buyers)->shuffle()->values();

        // Create a few inquiry conversations.
        $conversations = [];
        for ($i=0; $i < $buyers->count(); $i++) {
            $conversations[] = $this->createInquiryConversation(
                [],
                $seller,
                $buyersShuffled->get($i)
            );
        }
        $conversations = collect($conversations);

        // Make the filter request.
        $response = $this->json('POST', route('business-inquiry.index'), [
            'sortKey' => 'inquirer_za',
        ]);

        // Make sure we got the correct listings back
        $data = r_collect($response->json()['data']);
        $this->assertCount($conversations->count(), $data);

        $this->assertEquals(
            $buyers->reverse()->pluck('id')->values(),
            $data->map->get('space')->map->get('buyer_user_id')->values()
        );
    }

    /**
    * @test
    */
    public function it_does_not_include_unapproved_members()
    {
        $buyer = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], null, $buyer)->space;
        $requestUser = $requested = factory('App\User')->create();

        // Request a member
        $this->post(
            route('exchange-spaces.member.store', ['id' => $space->id]),
            ['user_id' => $requestUser->id, 'all' => 1]
        );

        $member = $space->fresh()->membersIncludingUnapproved->where('user_id', $requestUser->id)->first();
        $this->assertFalse($member->approved);

        // Sign in the requested user.
        $this->signInWithEvents($requestUser);
        $response = $this->json('POST', route('business-inquiry.index'));
    }
}
