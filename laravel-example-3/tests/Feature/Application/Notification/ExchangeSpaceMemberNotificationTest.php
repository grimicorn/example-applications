<?php

namespace Tests\Feature\Application\Notification;

use Tests\TestCase;
use App\Mail\NewNotification;
use App\ExchangeSpaceNotification;
use Illuminate\Support\Facades\Mail;
use App\Support\ExchangeSpace\MemberRole;
use Tests\Support\HasExchangeSpaceCreators;
use Tests\Support\HasNotificationTestHelpers;
use App\Support\Notification\NotificationType;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class ExchangeSpaceMemberNotificationTest extends TestCase
{
    use RefreshDatabase;
    use HasExchangeSpaceCreators;
    use HasNotificationTestHelpers;

    /**
    * @test
    */
    public function it_sends_a_buyer_advisor_added_notification()
    {
        $type = NotificationType::ADDED_ADVISOR_BUYER;
        $buyer = $this->signInWithEvents();
        $seller = factory('App\User')->create();
        $space = $this->createSpaceConversation([], $seller, $buyer)->space;
        $requestedUser = factory('App\User')->create();
        $data = ['user_id' => $requestedUser->id];

        // Set all members to be active and approved.
        $space->allMembers->each->activate();

        // Have the buyer request a member
        $response = $this->post(route('exchange-spaces.member.store', ['id' => $space->id]), $data);

        // Sign in the seller.
        $this->signInWithEvents($seller);

        // Start the mail fake.
        Mail::fake();

        // Add the user.
        $response = $this->post(route('exchange-spaces.member.store', ['id' => $space->id]), $data);

        // Get the members excluding the requested member and seller.
        $space = $space->fresh();
        $members = $space->members->whereNotIn('user_id', [$seller->id, $requestedUser->id]);

        // Should be all of the remaining members
        $this->assertNotificationCount($members->count(), $type);

        // We also need to notify the new member
        $this->assertNotificationCount(1, NotificationType::EXCHANGE_SPACE_MEMBER_WELCOME);

        // Make sure the data was stored
        $this->assertMembersNotificationInDatabase($members, $type, [
            'requested_member_name' => $requestedUser->name,
            'buyer_name' => $space->buyer_user->name,
            'requested_member_id' => $space->members()->where('user_id', $requestedUser->id)->first()->id,
        ]);
    }

    /**
    * @test
    */
    public function it_sends_a_seller_advisor_added_notification()
    {
        $type = NotificationType::ADDED_ADVISOR_SELLER;
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller)->space;
        $data = [
            'user_id' => $requestUserId = factory('App\User')->create()->id,
        ];

        // Set all members to be active and approved.
        $space->allMembers->each(function ($member) {
            $member->approved = true;
            $member->active = true;
            $member->save();
        });

        // Start the mail fake.
        Mail::fake();

        // Add the member
        $response = $this->post(route('exchange-spaces.member.store', ['id' => $space->id]), $data);

        // Get the members excluding the requested member and seller.
        $members = $space->fresh()->members->whereNotIn('user_id', [$seller->id, $requestUserId]);

        // Should be all of the remaining members
        $this->assertCount($members->count(), $this->getEmailNotificationsByType($type));

        // We also need to notify the new member
        $this->assertNotificationCount(1, NotificationType::EXCHANGE_SPACE_MEMBER_WELCOME);

        // Make sure the data was stored
        $this->assertMembersNotificationInDatabase($members, $type);
    }

    /**
    * @test
    */
    public function it_sends_a_removed_advisor_buyer_notification()
    {
        $type = NotificationType::REMOVED_ADVISOR_BUYER;

        // Create a space and member
        $space = $this->createSpaceConversation()->space;
        $member = $this->addExchangeSpaceAdvisor($space, $isSellerAdvisor = false);
        $space->allMembers()->get()->each->activate();
        $this->signInWithEvents($member->user);

        // Deactivate the member
        Mail::fake();
        $response = $this->delete(
            route('exchange-spaces.member.destroy', [
                'id' => $space->id,
                'm_id' => $member->id,
            ]),
            [
                'exit_message' => $exit_message = $this->faker->paragraphs(3, true)
            ]
        );

        // Get the members excluding the requested member and seller.
        $members = $space->members()->whereNotIn('user_id', [$member->user->id])->get();

        // Should be all of the remaining members
        $this->assertCount($members->count(), $this->getEmailNotificationsByType($type));

        // Make sure the data was stored
        $this->assertMembersNotificationInDatabase($members, $type, [
            'removed_member_name' => $member->user->name,
            'exit_message' => $exit_message,
        ]);

        // Make sure the notifications where cleared
        $this->assertCount(0, $this->getAllMemberDatabaseNotifications($space, $member->user));

        // Make sure the other possible notifications are not sent
        $this->assertNotificationCount(0, NotificationType::REMOVED_ADVISOR_SELLER);
        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::REMOVED_ADVISOR_SELLER);
        $this->assertNotificationCount(0, NotificationType::REMOVED_BUYER);
        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::REMOVED_BUYER);
        $this->assertNotificationCount(0, NotificationType::SELLER_REMOVED_ADVISOR);
        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::SELLER_REMOVED_ADVISOR);
    }

    /**
    * @test
    */
    public function it_sends_a_removed_advisor_seller_notification()
    {
        $type = NotificationType::REMOVED_ADVISOR_SELLER;

        // Create a space and member
        $space = $this->createSpaceConversation()->space;
        $member = $this->addExchangeSpaceAdvisor($space, $isSellerAdvisor = true);
        $space->allMembers()->get()->each->activate();
        $this->signInWithEvents($member->user);

        // Deactivate the member
        Mail::fake();
        $member->deactivate();

        // Get the members excluding the requested member and seller.
        $members = $space->members()->whereNotIn('user_id', [$member->user->id])->get();

        // Should be all of the remaining members
        $this->assertCount($members->count(), $this->getEmailNotificationsByType($type));

        // Make sure the data was stored
        $this->assertMembersNotificationInDatabase($members, $type, [
            'removed_member_name' => $member->user->name,
        ]);

        // Make sure the notifications where cleared
        $this->assertCount(0, $this->getAllMemberDatabaseNotifications($space, $member->user));

        // Make sure the other possible notifications are not sent
        $this->assertNotificationCount(0, NotificationType::REMOVED_BUYER);
        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::REMOVED_BUYER);
        $this->assertNotificationCount(0, NotificationType::REMOVED_ADVISOR_BUYER);
        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::REMOVED_ADVISOR_BUYER);
        $this->assertNotificationCount(0, NotificationType::SELLER_REMOVED_ADVISOR);
        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::SELLER_REMOVED_ADVISOR);
    }

    /**
    * @test
    */
    public function it_does_not_send_a_removed_advisor_seller_notification_when_declining_a_request()
    {
        $type = NotificationType::REMOVED_ADVISOR_SELLER;
        $buyer = $this->signInWithEvents();
        $seller = factory('App\User')->create();
        $space = $this->createSpaceConversation([], $seller, $buyer)->space;
        $data = [
            'user_id' => $requestUserId = factory('App\User')->create()->id,
        ];
        $response = $this->post(route('exchange-spaces.member.store', ['id' => $space->id]), $data);

        // Make sure the member was requested correctly.
        $member = $space->fresh()->membersIncludingUnapproved->where('user_id', $requestUserId)->first();
        $this->assertFalse($member->approved);

        // Sign in the seller.
        $this->signInWithEvents($seller);

        // Start the mail fake.
        Mail::fake();

        // Deny the user.
        $response = $this->delete(
            route('exchange-spaces.member.destroy', ['id' => $space->id, 'm_id' => $member->id])
        );

        // Get the members excluding the requested member and seller.
        $members = $space->members()->whereNotIn('user_id', [$member->user->id])->get();

        // Should be all of the remaining members
        $this->assertCount(0, $this->getEmailNotificationsByType($type));

        // Make sure the data was stored
        $this->assertMembersNotificationDatabaseMissing($members, $type, [
            'removed_member_name' => $member->user->name,
        ]);

        // Make sure the notifications where cleared
        $this->assertCount(0, $this->getAllMemberDatabaseNotifications($space, $member->user));

        // Make sure the other possible notifications are not sent
        $this->assertNotificationCount(0, NotificationType::REMOVED_BUYER);
        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::REMOVED_BUYER);
        $this->assertNotificationCount(0, NotificationType::REMOVED_ADVISOR_BUYER);
        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::REMOVED_ADVISOR_BUYER);
        $this->assertNotificationCount(0, NotificationType::SELLER_REMOVED_ADVISOR);
        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::SELLER_REMOVED_ADVISOR);
    }

    /**
     * @test
     */
    public function it_sends_a_removed_buyer_notification()
    {
        $type = NotificationType::REMOVED_BUYER;

        // Create a space and member
        $space = $this->createSpaceConversation()->space;
        $buyerAdvisor = $this->addExchangeSpaceAdvisor($space, $isSellerAdvisor = false);
        $sellerAdvisor = $this->addExchangeSpaceAdvisor($space, $isSellerAdvisor = true);
        $space->allMembers()->get()->each->activate();
        $buyer = $space->members()->where('user_id', $space->buyer_user->id)->first();
        $this->signInWithEvents($buyer->user);

        // Disable all email notifications since this should
        // always send a notification no matter what
        $this->disableMemberEmailNotifications($space->allMembers()->get());

        // Deactivate the member
        Mail::fake();
        $response = $this->delete(route('exchange-spaces.member.destroy', ['id' => $space->id, 'm_id' => $buyer->id]));

        // Get the members excluding the requested member.
        $members = $space->members()->whereNotIn('user_id', [$buyer->user->id])->get();

        // Should be all of the remaining members
        $this->assertCount(3, $this->getEmailNotificationsByType($type));

        // Make sure the data was stored
        $this->assertMembersNotificationInDatabase($members, $type, ['removed_member_name' => $buyer->user->name]);

        // Make sure the notifications where cleared
        $this->assertCount(0, $this->getAllMemberDatabaseNotifications($space, $buyer->user));

        // Make sure the other possible notifications are not sent
        $this->assertNotificationCount(0, NotificationType::REMOVED_ADVISOR_SELLER);
        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::REMOVED_ADVISOR_SELLER);
        $this->assertNotificationCount(0, NotificationType::REMOVED_ADVISOR_BUYER);
        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::REMOVED_ADVISOR_BUYER);
        $this->assertNotificationCount(0, NotificationType::SELLER_REMOVED_ADVISOR);
        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::SELLER_REMOVED_ADVISOR);
    }

    /**
    * @test
    */
    public function it_sends_an_advisor_a_notification_when_a_seller_removes_them()
    {
        $type = NotificationType::SELLER_REMOVED_ADVISOR;

        // Create an exchange space
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller)->space;

        // Add a member to the exchange space.
        $removedMember = factory('App\ExchangeSpaceMember')->states('seller-advisor', 'approved')->create([
            'exchange_space_id' => $space->id,
        ]);
        $this->assertTrue($removedMember->active);

        // Disabled the members notifications since this notification should always be sent no matter what.
        $removedMembers = collect([$removedMember]);
        $this->disableMemberEmailNotifications($removedMembers);

        // Delete the user.
        Mail::fake();
        $removedMember->deactivate();

        // Make sure notification was sent to all remaining members except the seller.
        $members = $removedMembers->concat($space->members()->where('role', '!=', MemberRole::SELLER)->get());
        $this->assertCount($members->count(), $this->getEmailNotificationsByType($type));
        $this->assertMembersNotificationInDatabase($members->map->fresh(), $type, [
            'removed_member_name' => $removedMember->user->name,
            'removed_member_id' => $removedMember->id,
        ]);

        // Make sure the notifications where cleared
        $notifications = $this->getAllMemberDatabaseNotifications($space, $removedMember->user);
        $this->assertCount(1, $notifications);
        $this->assertEquals($notifications->first()->type, $type);

        // Get all of the remaining members.
        $members = $space->members()->get();

        // Make sure the other possible notifications are not sent
        $this->assertNotificationCount(0, NotificationType::REMOVED_BUYER);
        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::REMOVED_BUYER);
        $this->assertNotificationCount(0, NotificationType::REMOVED_ADVISOR_SELLER);
        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::REMOVED_ADVISOR_SELLER);
        $this->assertNotificationCount(0, NotificationType::REMOVED_ADVISOR_BUYER);
        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::REMOVED_ADVISOR_BUYER);
    }
}
