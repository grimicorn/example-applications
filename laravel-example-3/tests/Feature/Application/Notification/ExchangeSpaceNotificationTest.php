<?php

namespace Tests\Feature\Application\Notification;

use App\User;
use App\Listing;
use Tests\TestCase;
use App\ExchangeSpace;
use App\ExchangeSpaceMember;
use App\Mail\NewNotification;
use App\ConversationNotification;
use App\ExchangeSpaceNotification;
use Illuminate\Support\Facades\Mail;
use App\Support\ExchangeSpaceDealType;
use Tests\Support\HasExchangeSpaceCreators;
use Tests\Support\HasNotificationTestHelpers;
use App\Support\Notification\NotificationType;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class ExchangeSpaceNotificationTest extends TestCase
{
    use RefreshDatabase, HasExchangeSpaceCreators, HasNotificationTestHelpers;

    /**
    * @test
    */
    public function it_sends_a_exchange_space_deleted_notification()
    {
        $type = NotificationType::DELETED_EXCHANGE_SPACE;

        // Make a space.
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller)->space;

        // Set all members to be active and approved.
        $space->allMembers->each->activate();

        // Disable all email notificaitons since this should
        // always send a notification no matter what
        $this->disableMemberEmailNotifications($space->allMembers()->get());

        // Start mail faking
        Mail::fake();

        // Store the members before deleting the exchange space.
        $members = $space->fresh()->members->whereNotIn('user_id', [auth()->id()]);

        // Delete the space.
        $this->delete(
            route('exchange-spaces.destroy', ['id' => $space->id]),
            ['close_message' => $close_message = $this->faker->paragraphs(3, true)]
        );

        // Make sure the emails were sent.
        // Should be all of the other members.
        $this->assertNotificationCount($members->count(), $type);

        // Make sure the data was stored
        $this->assertMembersNotificationInDatabase($members, $type, [
            'close_message' => $close_message,
        ]);
    }

    /**
    * @test
    */
    public function it_does_not_notify_inactive_users()
    {
        // Make a space.
        $seller = $this->signInWithEvents();
        $buyer = factory('App\User')->create();
        $space = $this->createSpaceConversation([], $seller, $buyer)->space;

        // Set buyer to approved but not active.
        $buyerMember = $space->allMembers->filter(function ($member) use ($buyer) {
            return $member->user->id === $buyer->id;
        })->first();
        $buyerMember->active = false;
        $buyerMember->approved = true;
        $buyerMember->save();

        // Start mail faking
        Mail::fake();

        // Store the members before deleting the exchange space.
        $members = $space->fresh()->allMembers->whereNotIn('user_id', [auth()->id()]);

        // Delete the space.
        $space->delete();

        // Make sure the correct emails were sent.
        // Should be all of the other members EXCEPT for one (Buyer).
        $memberCount = $members->count() - 1;
        Mail::assertSent(NewNotification::class, $memberCount);

        // Make sure the data was stored
        $this->assertDatabaseMissing(
            'exchange_space_notifications',
            $this->assertDatabaseFields($buyerMember, NotificationType::DELETED_EXCHANGE_SPACE)
        );
    }

    /**
    * @test
    */
    public function it_does_not_notify_unapproved_users()
    {
        // Make a space.
        $seller = $this->signInWithEvents();
        $buyer = factory('App\User')->create();
        $space = $this->createSpaceConversation([], $seller, $buyer)->space;

        // Set buyer to approved but not active.
        $buyerMember = $space->allMembers->filter(function ($member) use ($buyer) {
            return $member->user->id === $buyer->id;
        })->first();
        $buyerMember->active = false;
        $buyerMember->approved = false;
        $buyerMember->save();

        // Start mail faking
        Mail::fake();

        // Store the members before deleting the exchange space.
        $members = $space->fresh()->allMembers->whereNotIn('user_id', [auth()->id()]);

        // Delete the space.
        $space->delete();

        // Make sure the correct emails were sent.
        // Should be all of the other members EXCEPT for one (Buyer).
        $memberCount = $members->count() - 1;
        Mail::assertSent(NewNotification::class, $memberCount);

        // Make sure the data was stored
        $this->assertDatabaseMissing(
            'exchange_space_notifications',
            $this->assertDatabaseFields($buyerMember, NotificationType::DELETED_EXCHANGE_SPACE)
        );
    }

    /**
    * @test
    */
    public function it_does_not_notify_if_a_user_has_disabled_all_notifications()
    {
        // Make a space.
        $seller = $this->signInWithEvents();
        $buyer = factory('App\User')->create();
        $buyer->emailNotificationSettings->enable_all = false;
        $buyer->emailNotificationSettings->save();
        $space = $this->createSpaceConversation([], $seller, $buyer)->space;

        // Set all members to be active and approved.
        $space->allMembers->each(function ($member) {
            $member->approved = true;
            $member->active = true;
            $member->save();
        });

        // Start mail fake
        Mail::fake();

        // Store the members before deleting the exchange space.
        $members = $space->fresh()->allMembers->whereNotIn('user_id', [auth()->id()]);

        // Delete the space.
        $space->delete();

        // Make sure the emails were sent.
        $this->assertNotificationCount($members->count(), NotificationType::DELETED_EXCHANGE_SPACE);

        // Make sure the data was stored
        $this->assertMembersNotificationInDatabase($members, NotificationType::DELETED_EXCHANGE_SPACE);
    }

    /**
    * @test
    */
    public function it_sends_a_new_buyer_inquiry_notification()
    {
        // Create the exchange space users here so they do not fire off notifications
        $seller = factory('App\User')->create();
        $buyer = $this->signInWithEvents();
        $listing = factory('App\Listing')->create([
            'user_id' => $seller->id,
        ]);

        // Start mail fake
        Mail::fake();

        // Create an exchange space of business inquiry
        $response = $this->post(route('business-inquiry.store'), [
            'listing_id' => $listing->id,
            'body' => $body = 'Contact message',
        ]);

        // Make sure the notification was sent.
        $space = ExchangeSpace::latest()->first();
        $members = $space->members()->where('user_id', $seller->id)->get();
        $this->assertNotificationCount(1, NotificationType::NEW_BUYER_INQUIRY);
        $this->assertMembersNotificationInDatabase($members, NotificationType::NEW_BUYER_INQUIRY);

        // Make sure the message notitfication was not sent.
        $this->assertNotificationCount(0, NotificationType::MESSAGE);
        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::MESSAGE);
        $this->assertNotificationCount(0, NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION);
        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION);
    }

    /**
    * @test
    */
    public function it_sends_a_space_created_notification()
    {
        $type = NotificationType::NEW_EXCHANGE_SPACE;

        // Sign in the seller
        $seller = $this->signInWithEvents();

        // Start mail fake
        Mail::fake();

        // Make an inquiry.
        $inquiry = $this->createInquiryConversation([], $seller)->space;

        // Set all members to be active and approved.
        $inquiry->allMembers->each->activate();
        $this->enableMemberEmailNotifications($inquiry->allMembers);

        // Accept the inquiry
        $inquiry->acceptInquiry();
        $space = $inquiry->fresh();

        // Get all members except the seller.
        $members = $space->members()->whereNotIn('user_id', [$seller->id])->get();

        // Should be all of the remaining members
        $this->assertCount($members->count(), $this->getEmailNotificationsByType($type));

        // Make sure the data was stored
        $this->assertMembersNotificationInDatabase($members, $type);
    }

    /**
    * @test
    */
    public function it_sends_a_deal_stage_nda_notification()
    {
        $type = NotificationType::DEAL_STAGE_NDA;

        // Sign in the seller
        $seller = $this->signInWithEvents();

        // Make a space and make sure its status is Pre-NDA.
        $space = $this->createSpaceConversation([], $seller)->space;
        $space->deal = ExchangeSpaceDealType::PRE_NDA;
        $space->save();

        // Set all members to be active and approved.
        $space->allMembers->each(function ($member) {
            $member->activate();

            $member->user->emailNotificationSettings->enable_all = true;
            $member->user->emailNotificationSettings->save();
        });

        // Start mail fake
        Mail::fake();

        // Set the deal to Signed NDA
        $space->deal = ExchangeSpaceDealType::SIGNED_NDA;
        $space->save();

        // Make sure the emails were sent. Should be all of the other members except seller.
        $members = $space->fresh()->allMembers->whereNotIn('user_id', [auth()->id()]);
        $this->assertCount($members->count(), $this->getEmailNotificationsByType($type));

        // Make sure the data was stored
        $this->assertMembersNotificationInDatabase($members, $type);
    }

    /**
    * @test
    */
    public function it_sends_a_deal_stage_loi_signed_notification()
    {
        // Sign in the seller
        $seller = $this->signInWithEvents();

        // Make a space and make sure its status is Pre-NDA.
        $space = $this->createSpaceConversation([], $seller)->space;
        $space->deal = ExchangeSpaceDealType::SIGNED_NDA;
        $space->save();

        // Set all members to be active and approved.
        $space->allMembers->each(function ($member) {
            $member->approved = true;
            $member->active = true;
            $member->save();

            $member->user->emailNotificationSettings->enable_all = true;
            $member->user->emailNotificationSettings->save();
        });

        // Start mail fake
        Mail::fake();

        // Advance the status to LOI Signed.
        $response = $this->patch(route('exchange-spaces.advance-stage.update', ['id' => $space->id]));

        // Make sure the emails were sent. Should be all of the other members except seller.
        $members = $space->fresh()->members->whereNotIn('user_id', [auth()->id()]);
        Mail::assertSent(NewNotification::class, $members->count());

        // Make sure the data was stored
        $this->assertMembersNotificationInDatabase($members, NotificationType::DEAL_UPDATED);
    }

    /**
    * @test
    */
    public function it_sends_a_deal_stage_complete_notification()
    {
        // Sign in the seller
        $seller = $this->signInWithEvents();

        // Make a space and make sure its status is LOI Signed.
        $space = $this->createSpaceConversation([], $seller)->space;
        $space->deal = ExchangeSpaceDealType::LOI_SIGNED;
        $space->save();

        // Set all members to be active and approved.
        $space->allMembers->each->active();
        $this->enableMemberEmailNotifications($space->allMembers()->get());

        // Make another space for the same listing so we can check for a deleted notification
        $otherSpace = $this->createSpaceConversation([], $seller)->space;
        $otherSpace->allMembers->each->active();
        $otherSpace->listing_id = $space->listing_id;
        $otherSpace->save();
        $this->enableMemberEmailNotifications($otherSpace->allMembers()->get());

        // Make an inquiry for the same listing so we can check the rejection email was sent.
        $inquiry = $this->createInquiryConversation([], $seller)->space;
        $inquiry->allMembers->each->active();
        $inquiry->listing_id = $space->listing_id;
        $inquiry->save();
        $this->enableMemberEmailNotifications($inquiry->allMembers()->get());

        // Start mail fake
        Mail::fake();

        // Advance the status to Complete.
        $space->advanceStage();

        // Check the completed space
        $members = $space->fresh()->members->whereNotIn('user_id', [auth()->id()]);
        $this->assertNotificationCount($members->count(), NotificationType::DEAL_UPDATED);
        $this->assertMembersNotificationInDatabase($members, NotificationType::DEAL_UPDATED);

        // Check the not completed space
        $otherMembers = $otherSpace->members()->whereNotIn('user_id', [auth()->id()])->get();
        $this->assertNotificationCount(0, NotificationType::DELETED_EXCHANGE_SPACE);
        $this->assertMembersNotificationDatabaseMissing($otherMembers, NotificationType::DELETED_EXCHANGE_SPACE);

        // Check the not completed inquiry
        $inquiryMembers = $inquiry->members()->whereNotIn('user_id', [auth()->id()])->get();
        $this->assertNotificationCount(0, NotificationType::REJECTED_INQUIRY);
        $this->assertMembersNotificationDatabaseMissing($inquiryMembers, NotificationType::REJECTED_INQUIRY);
    }

    /**
    * @test
    * @group failing
    */
    public function it_sends_historical_financial_available_notification()
    {
        // Make a space.
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller)->space;
        $space->historical_financials_public = false;
        $space->save();

        // Set all members to be active and approved.
        $space->allMembers->each(function ($member) {
            $member->approved = true;
            $member->active = true;
            $member->save();

            $member->user->emailNotificationSettings->enable_all = true;
            $member->user->emailNotificationSettings->save();
        });

        // Start mail faking
        Mail::fake();

        // Make the finacials publlic.
        $space->historical_financials_public = true;
        $space->save();

        // Make sure the emails were sent.
        // Should be all of the other members except the seller.
        $members = $space->fresh()->members->whereNotIn('user_id', [$space->seller_user->id]);
        $emailCount = $members->count();
        Mail::assertSent(NewNotification::class, $emailCount);

        // Make sure the data was stored
        $this->assertMembersNotificationInDatabase($members, NotificationType::HISTORICAL_FINANCIAL_AVAILABLE);
    }

    /**
    * @test
    * @group failing
    */
    public function it_sends_historical_financial_unavailable_notification()
    {
        $type = NotificationType::HISTORICAL_FINANCIAL_UNAVAILABLE;

        // Make a space.
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller)->space;
        $space->historical_financials_public = true;
        $space->save();

        // Set all members to be active and approved.
        $space->allMembers->each(function ($member) {
            $member->activate();

            $member->user->emailNotificationSettings->enable_all = true;
            $member->user->emailNotificationSettings->save();
        });

        // Start mail faking
        Mail::fake();

        // Make the finacials publlic.
        $space->historical_financials_public = false;
        $space->save();

        // Make sure the emails were sent.
        // Should be all of the other members except the seller.
        $members = $space->fresh()->members->whereNotIn('user_id', [$space->seller_user->id]);
        $emailCount = $members->count();
        Mail::assertSent(NewNotification::class, $emailCount);

        // Make sure the data was stored
        $this->assertMembersNotificationInDatabase($members, $type);
    }

    /**
    * @test
    */
    public function it_sends_a_buyer_inquiry_rejected_notification()
    {
        $seller = $this->signInWithEvents();

        // Make a space.
        $inquiry = $this->createInquiryConversation([], $seller)->space;

        // Store the members before rejecting the inquiry
        $members = $inquiry->fresh()->allMembers->whereNotIn('user_id', [auth()->id()]);

        // Make sure all members are active
        $members->each->activate();

        // Disable notifications for all members since users should always recieve this notification.
        $this->disableMemberEmailNotifications($members);

        Mail::fake();

        // Reject the inquiry
        $response = $this->delete(
            route('business-inquiry.acceptance.destroy', ['id' => $inquiry->id]),
            $data = [
                'reason' => $reason = 'Some reason',
                'explanation' => $message = 'Some explanation',
            ]
        );

        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::NEW_EXCHANGE_SPACE);
        $this->assertMembersNotificationDatabaseMissing($members, NotificationType::DELETED_EXCHANGE_SPACE);

        // Make sure the emails were sent.
        // Should be all of the other members.
        $this->assertNotificationCount($members->count(), NotificationType::REJECTED_INQUIRY);

        // Make sure the data was stored
        $this->assertMembersNotificationInDatabase($members, NotificationType::REJECTED_INQUIRY);

        // Check for rejected_reason and rejected_explanation
        $notification = ExchangeSpaceNotification::where('exchange_space_id', $inquiry->id)
        ->where('user_id', $members->first()->id)
        ->where('type', NotificationType::REJECTED_INQUIRY)
        ->get()->first();

        $this->assertNotNull($notification->rejected_reason);
        $this->assertNotNull($notification->rejected_explanation);

        // Make sure the data is passed through correctly
        $notification = $this->getEmailNotificationsByType(NotificationType::REJECTED_INQUIRY)->first()->notification;
        $viewData = $notification->sharedViewData();
        $this->assertEquals($reason, $notification->sharedViewData()['rejected_reason'] ?? '');
        $this->assertEquals($message, $notification->sharedViewData()['message'] ?? '');
    }

    /**
    * @test
    */
    public function it_removes_all_notifications_except_exchange_space_closed_when_deleting_an_exchange_space()
    {
        $seller = $this->signInWithEvents();
        $conversation = $this->createSpaceConversation([], $seller);
        $space = $conversation->space;
        $space->allMembers->each->activate();

        $this->createsExchangeSpaceNotifications($space, $conversation, $skipTypes = [
            NotificationType::REJECTED_INQUIRY,
            NotificationType::DELETED_EXCHANGE_SPACE
        ]);

        // Delete the space
        $space->delete();

        // Make sure all exchange space notifications are deleted (except rejection)
        $space_notifications = ExchangeSpaceNotification::where('exchange_space_id', $space->id)->get();
        $this->assertTrue($space_notifications->whereNotIn(
            'type',
            [NotificationType::DELETED_EXCHANGE_SPACE]
        )->isEmpty());

        // Make sure that one rejection inquiry exists.
        $this->assertFalse($space_notifications->where(
            'type',
            NotificationType::DELETED_EXCHANGE_SPACE
        )->isEmpty());

        // Make sure no conversation notifications exist
        $converstion_notifications = ConversationNotification::whereIn(
            'conversation_id',
            $space->conversations->pluck('id')
        )->get();
        $this->assertTrue($converstion_notifications->isEmpty());
    }

    /**
    * @test
    */
    public function it_removes_all_notifications_except_rejected_inquiry_when_rejecting_a_buyer_inquiry()
    {
        $seller = $this->signInWithEvents();
        $conversation = $this->createInquiryConversation([], $seller);
        $space = $conversation->space;
        $space->allMembers->each->activate();

        $this->createsExchangeSpaceNotifications($space, $conversation, $skipTypes = [
            NotificationType::REJECTED_INQUIRY,
            NotificationType::DELETED_EXCHANGE_SPACE
        ]);

        // Reject the inquiry
        $space->delete();

        // Make sure all exchange space notifications are deleted (except rejection)
        $space_notifications = ExchangeSpaceNotification::where('exchange_space_id', $space->id)->get();
        $this->assertTrue($space_notifications->whereNotIn(
            'type',
            [NotificationType::REJECTED_INQUIRY]
        )->isEmpty());

        // Make sure that one rejection inquiry exists.
        $this->assertFalse($space_notifications->where(
            'type',
            NotificationType::REJECTED_INQUIRY
        )->isEmpty());

        // Make sure no conversation notifications exist
        $converstion_notifications = ConversationNotification::whereIn(
            'conversation_id',
            $space->conversations->pluck('id')
        )->get();
        $this->assertTrue($converstion_notifications->isEmpty());
    }

    /**
    * @test
    */
    public function it_removes_all_notifications_when_a_member_becomes_inactive()
    {
        $conversation = $this->createSpaceConversation();
        $space = $conversation->space;

        // Add some advisors
        $sellerAdvisor = $this->addExchangeSpaceAdvisor($space, $isSellerAdvisor = true);
        $buyerAdvisor = $this->addExchangeSpaceAdvisor($space, $isSellerAdvisor = false);

        // Create some notifications
        $this->createsExchangeSpaceNotifications($space, $conversation, $skipTypes = [
            NotificationType::REJECTED_INQUIRY,
            NotificationType::DELETED_EXCHANGE_SPACE
        ]);

        // Deactivate the buyer advisor
        $this->signInWithEvents($buyerAdvisor->user);
        $buyerAdvisor->deactivate();

        // Get the existing notifications
        $space_notifications = ExchangeSpaceNotification::where('exchange_space_id', $space->id)->get();
        $conversation_notifications = ConversationNotification::whereIn(
            'conversation_id',
            $space->conversations->pluck('id')
        )->get();

        // Make sure all of the deactivated member space notifications are deleted
        $this->assertTrue(
            $space_notifications->where('user_id', $buyerAdvisor->user->id)
            ->whereNotIn('type', NotificationType::SELLER_REMOVED_ADVISOR)->isEmpty()
        );

        // Make sure all of the active member space notifications are not deleted
        $space_notifications->whereNotIn('user_id', [$buyerAdvisor->user->id])
        ->groupBy('user_id')
        ->each(function ($notifications) {
            $this->assertFalse($notifications->isEmpty());
        });

        // Make sure all of the deactivated member conversation notifications are deleted
        $this->assertTrue($conversation_notifications->where('user_id', $buyerAdvisor->user->id)->isEmpty());

        // Make sure all of the active member conversation notifications are not deleted
        $conversation_notifications->whereNotIn('user_id', [$buyerAdvisor->user->id])
        ->groupBy('user_id')
        ->each(function ($notifications) {
            $this->assertFalse($notifications->isEmpty());
        });
    }

    /**
    * @test
    */
    public function it_sends_exchange_space_deleted_notifications_to_all_active_members_when_a_seller_deletes_a_listing()
    {
        $seller = $this->signInWithEvents();
        $listing = factory('App\Listing')->create(['user_id' => $seller->id]);

        // Create a few spaces
        $spaces = collect([]);
        for ($i=0; $i < 4; $i++) {
            $space = $this->createSpaceConversation([], $seller)->space;
            $space->listing_id = $listing->id;
            $space->save();
            $spaces->push($space->fresh());
            $space->allMembers()->get()->each->activate();
        }

        // Delete the listing
        Mail::fake();
        $listing->fresh()->delete();

        // Make sure the correct notifications where sent.
        $members = collect([]);
        foreach ($spaces as $space) {
            $members = $members->concat($space->members()->whereNotIn('user_id', [auth()->id()])->get());
        }

        $this->assertNotificationCount($members->count(), NotificationType::DELETED_EXCHANGE_SPACE);
        $this->assertMembersNotificationInDatabase($members, NotificationType::DELETED_EXCHANGE_SPACE);
    }

    /**
     * @test
     */
    public function it_sends_inquiry_rejected_notifications_to_all_active_members_when_a_seller_deletes_a_listing()
    {
        $seller = $this->signInWithEvents();
        $listing = factory('App\Listing')->create(['user_id' => $seller->id]);

        // Create a few spaces
        $inquiries = collect([]);
        for ($i = 0; $i < 4; $i++) {
            $inquiry = $this->createInquiryConversation([], $seller)->space;
            $inquiry->listing_id = $listing->id;
            $inquiry->save();
            $inquiries->push($inquiry->fresh());
            $inquiry->allMembers()->get()->each->activate();
        }

        // Delete the listing
        Mail::fake();
        $listing->fresh()->delete();

        // Make sure the correct notifications where sent.
        $members = collect([]);
        foreach ($inquiries as $inquiry) {
            $members = $members->concat($inquiry->members()->whereNotIn('user_id', [auth()->id()])->get());
        }
        $this->assertNotificationCount($members->count(), NotificationType::REJECTED_INQUIRY);
        $this->assertMembersNotificationInDatabase($members, NotificationType::REJECTED_INQUIRY);
    }

    /**
     * @test
     */
    public function it_only_sends_exchange_space_closed_to_the_closed_exchange_space()
    {
        // If I am a buyer in one exchange space for a listing and it is completed by the seller then I should receive the completed exchange space notification...
        // then if I am a seller advisor in another COMPLETED exchange space for the same listing I should receive the listing closed notification...
        // then if I am a buyer advisor in another exchange space for the same listing I should receive the listing closed notification

        $listing = factory(Listing::class)->create();
        $user = factory(User::class)->create();

        // The exchange spaces
        $space1 = factory(ExchangeSpace::class)->states('completed')->create([
            'listing_id' => $listing->id,
            'user_id' => $listing->user->id,
        ]);
        $space2 = factory(ExchangeSpace::class)->states('completed')->create([
            'listing_id' => $listing->id,
            'user_id' => $listing->user->id,
        ]);
        $space3 = factory(ExchangeSpace::class)->states('not-inquiry-or-completed')->create([
            'listing_id' => $listing->id,
            'user_id' => $listing->user->id,
        ]);

        // The members for exchange space 1
        factory(ExchangeSpaceMember::class)->states('approved', 'seller')->create([
            'exchange_space_id' => $space1->id,
            'user_id' => $space1->user->id,
        ]);
        $buyer = factory(ExchangeSpaceMember::class)->states('approved', 'buyer')->create([
            'exchange_space_id' => $space1->id,
            'user_id' => $user->id,
        ]);

        // The members for exchange space 2
        factory(ExchangeSpaceMember::class)->states('approved', 'seller')->create([
            'exchange_space_id' => $space2->id,
            'user_id' => $space2->user->id,
        ]);
        factory(ExchangeSpaceMember::class)->states('approved', 'buyer')->create([
            'exchange_space_id' => $space2->id,
        ]);
        $buyerAdvisor = factory(ExchangeSpaceMember::class)->states('approved', 'buyer-advisor')->create([
            'exchange_space_id' => $space2->id,
            'user_id' => $user->id,
        ]);

        // The members for exchange space 3
        factory(ExchangeSpaceMember::class)->states('approved', 'seller')->create([
            'exchange_space_id' => $space3->id,
            'user_id' => $space3->user->id,
        ]);
        factory(ExchangeSpaceMember::class)->states('approved', 'buyer')->create([
            'exchange_space_id' => $space3->id,
        ]);
        $sellerAdvisor = factory(ExchangeSpaceMember::class)->states('approved', 'seller-advisor')->create([
            'exchange_space_id' => $space3->id,
            'user_id' => $user->id,
        ]);

        $members = ExchangeSpaceMember::whereIn('exchange_space_id', [
            $space1->id,
            $space2->id,
            $space3->id,
        ])->where('role', '!=', 1)->get();
        $memberCount = $members->count();

        // Start mail faking
        Mail::fake();

        // Close the listing via exchange space 1
        $this->signIn($listing->user);
        $this->delete(route('listing.destroy', ['id' => $space1->listing->id, 'space_id' => $space1->id]));

        $notify = $this->getEmailNotificationsByType(NotificationType::DELETED_EXCHANGE_SPACE)
        ->map(function ($item) {
            return optional($item->notification->sharedViewData()['space'])->id;
        });

        $this->assertNotificationCount($memberCount, NotificationType::DELETED_EXCHANGE_SPACE);

        $this->assertFalse($space1->fresh()->useListingClosed());
        $this->assertTrue($space2->fresh()->useListingClosed());
        $this->assertTrue($space3->fresh()->useListingClosed());
    }
}
