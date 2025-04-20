<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use App\Mail\NewNotification;
use Illuminate\Support\Facades\Mail;
use App\Support\ExchangeSpace\MemberRole;
use Tests\Support\HasExchangeSpaceCreators;
use App\Support\Notification\NotificationType;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class ExchangeSpaceMemberControllerTest extends TestCase
{
    use RefreshDatabase, HasExchangeSpaceCreators;

    /**
    * @test
    */
    public function it_allows_buyers_to_request_a_new_member()
    {
        $this->withoutExceptionHandling();
        $buyer = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], null, $buyer)->space;

        $data = [
            'user_id' => $requestUserId = factory('App\User')->create()->id,
        ];

        // Start mail fake
        Mail::fake();

        // Request a member
        $response = $this->post(route('exchange-spaces.member.store', ['id' => $space->id]), $data);

        // Make sure everything went correctly.
        $response->assertStatus(302);

        // Make sure the member was added correctly.
        $member = $space->fresh()->membersIncludingUnapproved->where('user_id', $requestUserId)->first();
        $this->assertNotNull($member);
        $this->assertFalse($member->approved);
        $this->assertEquals($space->currentMember->id, $member->requested_by_id);
        $this->assertEquals($requestUserId, $member->user_id);
        $this->assertTrue($member->pending);
        $this->assertEquals($space->id, $member->exchange_space_id);
        $this->assertEquals(MemberRole::BUYER_ADVISOR, $member->role);

        // Make sure the seller was notified.
        Mail::assertSent(NewNotification::class, function ($mail) use ($space) {
            return $mail->hasTo($space->seller_user->email) and
                   $mail->notification->type() === NotificationType::NEW_MEMBER_REQUESTED;
        });
    }

    /**
    * @test
    */
    public function it_only_allows_users_to_be_requested_or_added_once()
    {
        $buyer = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], null, $buyer)->space;
        $data = [
            'user_id' => $requestUserId = factory('App\User')->create()->id,
        ];

        // Try to add the user once.
        $response = $this->post(route('exchange-spaces.member.store', ['id' => $space->id]), $data);

        // Make sure everything went correctly.
        $response->assertStatus(302);

        // Try to add the user again.
        $response = $this->post(route('exchange-spaces.member.store', ['id' => $space->id]), $data);

        // Make sure the member was added correctly.
        $this->assertCount(1, $space->fresh()->membersIncludingUnapproved->where('user_id', $requestUserId));
    }

    /**
    * @test
    */
    public function it_allows_sellers_to_accept_buyer_requests_to_add_a_new_member()
    {
        $buyer = $this->signInWithEvents();
        $seller = factory('App\User')->create();
        $space = $this->createSpaceConversation([], $seller, $buyer)->space;
        $data = [
            'user_id' => $requestUserId = factory('App\User')->create()->id,
        ];
        $response = $this->post(route('exchange-spaces.member.store', ['id' => $space->id]), $data);

        // Make sure everything went correctly.
        $response->assertStatus(302);

        // Make sure the member was requested correctly.
        $member = $space->fresh()->membersIncludingUnapproved->where('user_id', $requestUserId)->first();
        $this->assertFalse($member->approved);

        // Sign in the seller.
        $this->signInWithEvents($seller);

        // Start the mail fake.
        Mail::fake();

        // Add the user.
        $response = $this->post(route('exchange-spaces.member.store', ['id' => $space->id]), $data);

        // The member should now be approved.
        $member = $member->fresh();
        $this->assertTrue($member->approved);
        $this->assertFalse($member->pending);

        // Make sure the data was saved.
        $this->assertTrue($member->fresh()->approved);
    }

    /**
     * @test
     */
    public function it_allows_sellers_to_deny_buyer_requests_to_add_a_new_member()
    {
        $buyer = $this->signInWithEvents();
        $seller = factory('App\User')->create();
        $space = $this->createSpaceConversation([], $seller, $buyer)->space;
        $data = [
            'user_id' => $requestUserId = factory('App\User')->create()->id,
        ];
        $response = $this->post(route('exchange-spaces.member.store', ['id' => $space->id]), $data);

        // Make sure everything went correctly.
        $response->assertStatus(302);

        // Make sure the member was requested correctly.
        $member = $space->fresh()->membersIncludingUnapproved->where('user_id', $requestUserId)->first();
        $this->assertFalse($member->approved);

        // Sign in the seller.
        $this->signInWithEvents($seller);

        // Start the mail fake.
        Mail::fake();

        // Deny the user.
        $response = $this->delete(
            route('exchange-spaces.member.destroy', [ 'id' => $space->id, 'm_id' => $member->id ])
        );

        // The member should not be approved.
        $member = $member->fresh();
        $this->assertTrue($member->trashed());
        $this->assertFalse($member->approved);
        $this->assertFalse($member->pending);
    }

    /**
    * @test
    */
    public function it_allows_sellers_to_add_a_new_member_to_an_exchange_space()
    {
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller)->space;
        $data = [
            'user_id' => $requestUserId = factory('App\User')->create()->id,
        ];
        $response = $this->post(route('exchange-spaces.member.store', ['id' => $space->id]), $data);

        // Make sure everything went correctly.
        $response->assertStatus(302);

        // Make sure the member was added correctly.
        $member = $space->fresh()->membersIncludingUnapproved->where('user_id', $requestUserId)->first();
        $this->assertNotNull($member);
        $this->assertTrue($member->approved);
        $this->assertEquals($space->currentMember->id, $member->requested_by_id);
        $this->assertEquals($requestUserId, $member->user_id);
        $this->assertEquals($space->id, $member->exchange_space_id);
        $this->assertFalse($member->pending);
        $this->assertEquals(MemberRole::SELLER_ADVISOR, $member->role);
        $this->assertTrue($member->active);
    }

    /**
    * @test
    */
    public function it_allows_sellers_to_re_add_a_removed_member_to_an_exchange_space()
    {
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller)->space;
        $data = [
            'user_id' => $requestUserId = factory('App\User')->create()->id,
        ];
        $response = $this->post(route('exchange-spaces.member.store', ['id' => $space->id]), $data);

        // Make sure everything went correctly.
        $response->assertStatus(302);

        // Make sure the member was added correctly.
        $member = $space->fresh()->membersIncludingUnapproved->where('user_id', $requestUserId)->first();
        $this->assertNotNull($member);
        $this->assertTrue($member->fresh()->active);

        // Now remove the member.
        $response = $this->delete(route('exchange-spaces.member.destroy', [
            'id' => $space->id,
            'm_id' => $member->id,
        ]));
        $this->assertFalse($member->fresh()->active);

        // Now added the member back to the exchange space.
        $response = $this->post(route('exchange-spaces.member.store', ['id' => $space->id]), $data);

        // Make sure the member was added correctly.
        $member = $member->fresh();
        $this->assertTrue($member->active);
        $this->assertFalse($member->trashed());
    }

    /**
    * @test
    */
    public function it_updates_the_requested_by_details_when_re_adding_a_removed_member()
    {
        $this->withoutExceptionHandling();
        // Add a seller advisor
        $space = $this->createSpaceConversation([], $seller = $this->signInWithEvents())->space;
        $member = $this->addExchangeSpaceAdvisor($space, $isSellerAdvisor = true, $user = null, $requestedById = $seller->id);

        // Remove the seller advisor
        $this->delete(route('exchange-spaces.member.destroy', ['id' => $space->id, 'm_id' => $member->id]));
        $member = $member->fresh();
        $this->assertTrue($member->is_seller_advisor);
        $this->assertEquals($seller->id, $member->requested_by_id);
        $this->assertTrue($member->trashed());
        $this->assertTrue($member->approved);
        $this->assertFalse($member->pending);
        $this->assertFalse($member->active);

        // Request re-adding as a buyer advisor
        $this->signInWithEvents($buyer = $space->buyerUser());
        $this->post(route('exchange-spaces.member.store', ['id' => $space->id]), ['user_id' => $member->user_id]);
        $member = $member->fresh();
        $this->assertTrue($member->is_buyer_advisor);
        // $this->assertEquals($buyer->id, $member->requested_by_id);
        $this->assertFalse($member->trashed());
        $this->assertFalse($member->approved);
        $this->assertTrue($member->pending);
        $this->assertTrue($member->active);

        // Re-add the member as a buyer advisor
        $this->signInWithEvents($space->sellerUser());
        $this->post(route('exchange-spaces.member.store', ['id' => $space->id]), ['user_id' => $member->user_id]);
        $member = $member->fresh();
        $this->assertTrue($member->is_buyer_advisor);
        $this->assertEquals($buyer->id, $member->requested_by_id);
        $this->assertFalse($member->trashed());
        $this->assertTrue($member->approved);
        $this->assertFalse($member->pending);
        $this->assertTrue($member->active);
    }

    /**
    * @test
    */
    public function it_only_allows_sellers_to_add_an_exchange_space_member()
    {
        Mail::fake();

        $buyer = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], null, $buyer)->space;
        $data = [
            'user_id' => $requestUserId = factory('App\User')->create()->id,
        ];
        $response = $this->post(route('exchange-spaces.member.store', ['id' => $space->id]), $data);

        // Make sure the member was not approved.
        $member = $space->fresh()->membersIncludingUnapproved->where('user_id', $requestUserId)->first();
        $this->assertFalse($member->approved);
    }

    /**
    * @test
    */
    public function it_allows_sellers_to_remove_a_user_from_an_exchange_space()
    {
        // Create an exchange space
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller)->space;

        // Add a member to the exchange space.
        $member = factory('App\ExchangeSpaceMember')->states('seller-advisor')->create([
            'exchange_space_id' => $space->id,
            'active' => true,
            'dashboard' => true,
        ]);
        $this->assertTrue($member->active);

        // Delete the user.
        $response = $this->delete(
            route('exchange-spaces.member.destroy', [
                'id' => $space->id,
                'm_id' => $member->id,
            ]),
            [
                'exit_message' => $exit_message = $this->faker->paragraphs(3, true)
            ]
        );

        // Make sure everything went correctly.
        $response->assertStatus(302);

        // Make sure the member is saved correctly
        $member = $member->fresh();
        $this->assertFalse($member->active);
        $this->assertTrue($member->removed_by_seller);
        $this->assertEquals($exit_message, $member->exit_message);
        $this->assertFalse($member->dashboard);
    }

    /**
    * @test
    */
    public function it_allows_an_advisor_to_remove_themselves()
    {
        // Create an exchange space
        $space = $this->createSpaceConversation([])->space;

        // Add a member to the exchange space.
        $member = factory('App\ExchangeSpaceMember')->states('seller-advisor')->create([
            'exchange_space_id' => $space->id,
            'active' => true,
            'dashboard' => true,
        ]);
        $this->assertTrue($member->active);

        // Sign in the user
        $this->signInWithEvents($member->user);

        // Delete the user.
        $response = $this->delete(
            route('exchange-spaces.member.destroy', [
                'id' => $space->id,
                'm_id' => $member->id,
            ]),
            [
                'exit_message' => $exit_message = $this->faker->paragraphs(3, true)
            ]
        );

        // Make sure everything went correctly.
        $response->assertStatus(302);

        // Make sure the member is saved correctly
        $member = $member->fresh();
        $this->assertFalse($member->active);
        $this->assertFalse($member->removed_by_seller);
        $this->assertEquals($exit_message, $member->exit_message);
        $this->assertFalse($member->dashboard);
    }

    /**
     * @test
     */
    public function it_allows_a_buyer_to_remove_themselves()
    {
        // Create an exchange space
        $buyer = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], null, $buyer)->space;
        $space->members->each(function($member) {
            $member->dashboard = true;
            $member->save();
            $member->activate();
        });
        $member = $space->buyerMember();

        // Delete the user.
        $response = $this->delete(
            route('exchange-spaces.member.destroy', [
                'id' => $space->id,
                'm_id' => $member->id,
            ]),
            [
                'exit_message' => $exit_message = $this->faker->paragraphs(3, true)
            ]
        );

        // Make sure everything went correctly.
        $response->assertStatus(302);

        // Make sure the buyer is saved correctly
        $member = $member->fresh();
        $this->assertEquals($exit_message, $member->exit_message);
        $this->assertFalse($member->removed_by_seller);
        $this->assertFalse($member->active);
        $this->assertFalse($member->dashboard);
    }

    /**
    * @test
    */
    public function it_removes_all_advisors_when_a_buyer_is_removed()
    {
        // Create an exchange space
        $buyer = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], null, $buyer)->space;
        $space->members->each->activate();
        $member = $space->buyerMember();

        // Add some advisors
        $buyerAdvisor = $this->addExchangeSpaceAdvisor($space, $isSellerAdvisor = false);
        $sellerAdvisor = $this->addExchangeSpaceAdvisor($space, $isSellerAdvisor = true);

        // Make sure all members are activated.
        $space->fresh()->members->each->activate();

        // Make sure all members are active.
        $this->assertTrue($space->sellerMember()->active);
        $this->assertTrue($space->buyerMember()->active);
        $this->assertTrue($buyerAdvisor->active);
        $this->assertTrue($sellerAdvisor->active);

        // Delete the user.
        $response = $this->delete(
            route('exchange-spaces.member.destroy', ['id' => $space->id, 'm_id' => $member->id])
        );

        $buyerAdvisor = $buyerAdvisor->fresh();
        $sellerAdvisor = $buyerAdvisor->fresh();
        $buyerMember = $space->buyerMember()->fresh();

        // Make sure the buyer and advisors where deactivated...
        $this->assertFalse($buyerAdvisor->active);
        $this->assertFalse($sellerAdvisor->active);
        $this->assertFalse($buyerMember->active);

        // Make sure all where not set as being deactivated by the seller.
        $this->assertFalse($buyerAdvisor->removed_by_seller);
        $this->assertFalse($sellerAdvisor->removed_by_seller);
        $this->assertFalse($buyerMember->removed_by_seller);

        // and the seller was not deactivated
        $this->assertTrue($space->sellerMember()->fresh()->active);
    }

    /**
     * @test
     */
    public function it_does_not_remove_all_advisors_when_an_advisor_is_removed()
    {
        // Create an exchange space
        $space = $this->createSpaceConversation([])->space;

        // Add a advisor member to remove
        $member = $this->addExchangeSpaceAdvisor($space, $isSellerAdvisor = false);
        $this->signInWithEvents($member->user);

        // Add some advisors
        $buyerAdvisor = $this->addExchangeSpaceAdvisor($space, $isSellerAdvisor = false);
        $sellerAdvisor = $this->addExchangeSpaceAdvisor($space, $isSellerAdvisor = true);

        // Make sure all members are activated.
        $space->fresh()->members->each->activate();

        // Make sure all members are active.
        $this->assertTrue($space->sellerMember()->active);
        $this->assertTrue($space->buyerMember()->active);
        $this->assertTrue($member->active);
        $this->assertTrue($buyerAdvisor->active);
        $this->assertTrue($sellerAdvisor->active);

        // Delete the user.
        $response = $this->delete(
            route('exchange-spaces.member.destroy', ['id' => $space->id, 'm_id' => $member->id])
        );

        // Make sure only the one member was deactivated
        $this->assertFalse($member->fresh()->active);
        $this->assertTrue($buyerAdvisor->fresh()->active);
        $this->assertTrue($sellerAdvisor->fresh()->active);
        $this->assertTrue($space->buyerMember()->fresh()->active);
        $this->assertTrue($space->sellerMember()->fresh()->active);
    }

    /**
    * @test
    */
    public function it_does_not_allow_buyers_to_remove_users_from_an_exchange_space()
    {
        // Create an exchange space
        $buyer = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], null, $buyer)->space;

        // Add a member to the exchange space.
        $member = factory('App\ExchangeSpaceMember')->states('seller-advisor')->create([
            'exchange_space_id' => $space->id,
            'active' => true,
        ]);
        $this->assertTrue($member->active);

        // Delete the user.
        $response = $this->delete(route('exchange-spaces.member.destroy', [
            'id' => $space->id,
            'm_id' => $member->id,
        ]));

        // Make sure the request is forbidden
        $response->assertStatus(403);

        // Make sure the member was not removed.
        $this->assertTrue($member->fresh()->active);
    }

    /**
    * @test
    */
    public function it_does_not_allow_buyer_advisors_to_remove_users_from_an_exchange_space_unless_they_are_leaving_an_exchange_space()
    {
        Mail::fake();

        // Create an exchange space
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller)->space;

        // Create and login a buyer advisor
        $buyerAdvisor = factory('App\ExchangeSpaceMember')
        ->states('buyer-advisor')
        ->create([
            'exchange_space_id' => $space->id,
            'active' => true,
        ]);
        $this->signInWithEvents($buyerAdvisor->user);

        // Add a member to the exchange space.
        $member = factory('App\ExchangeSpaceMember')->states('seller-advisor')->create([
            'exchange_space_id' => $space->id,
            'active' => true,
        ]);
        $this->assertTrue($member->active);

        // Delete the user.
        $response = $this->delete(route('exchange-spaces.member.destroy', [
            'id' => $space->id,
            'm_id' => $member->id,
        ]));

        // Make sure the request is forbidden
        $response->assertStatus(403);

        // Make sure the member was not removed.
        $this->assertTrue($member->fresh()->active);

        // Now try to delete the buyer advisor
        $response = $this->delete(route('exchange-spaces.member.destroy', [
            'id' => $space->id,
            'm_id' => $buyerAdvisor->id,
        ]));

        // Make sure the buyer advisor was removed.
        $this->assertFalse($buyerAdvisor->fresh()->active);
    }

    /**
    * @test
    */
    public function it_does_not_allow_seller_advisors_to_remove_users_from_an_exchange_space_unless_they_are_leaving_an_exchange_space()
    {
        Mail::fake();

        // Create an exchange space
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller)->space;

        // Create and login a seller advisor
        $sellerAdvisor = factory('App\ExchangeSpaceMember')
        ->states('seller-advisor')
        ->create([
            'exchange_space_id' => $space->id,
            'active' => true,
        ]);
        $this->signInWithEvents($sellerAdvisor->user);

        // Add a member to the exchange space.
        $member = factory('App\ExchangeSpaceMember')->states('seller-advisor')->create([
            'exchange_space_id' => $space->id,
            'active' => true,
        ]);
        $this->assertTrue($member->active);

        // Delete the user.
        $response = $this->delete(route('exchange-spaces.member.destroy', [
            'id' => $space->id,
            'm_id' => $member->id,
        ]));

        // Make sure the request is forbidden
        $response->assertStatus(403);

        // Make sure the member was not removed.
        $this->assertTrue($member->fresh()->active);

        // Now try to delete the seller advisor
        $response = $this->delete(route('exchange-spaces.member.destroy', [
            'id' => $space->id,
            'm_id' => $sellerAdvisor->id,
        ]));

        // Make sure the seller advisor was removed.
        $this->assertFalse($sellerAdvisor->fresh()->active);
    }

    /**
    * @test
    */
    public function it_does_not_allow_sellers_to_be_removed_from_an_exchange_space()
    {
        // Create an exchange space
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller)->space;
        $member = $space->members->where('user_id', $seller->id)->first();
        $this->assertTrue($member->fresh()->active);

        // Try to Delete the seller.
        $response = $this->delete(route('exchange-spaces.member.destroy', [
            'id' => $space->id,
            'm_id' => $member->id,
        ]));

        // Make sure the request is forbidden
        $response->assertStatus(403);

        // Make sure the seller was not deleted.
        $this->assertTrue($member->fresh()->active);
    }
}
