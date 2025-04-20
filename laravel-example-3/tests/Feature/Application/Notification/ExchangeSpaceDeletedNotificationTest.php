<?php

namespace Tests\Feature\Application\Notification;

use App\Listing;
use Tests\TestCase;
use App\ExchangeSpace;
use App\Support\Notification\NotificationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Support\Notification\ExchangeSpaceNotification;

// @codingStandardsIgnoreFile
class ExchangeSpaceDeletedNotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_selects_the_correct_email_message_when_a_listing_was_deleted_without_a_completed_exchange_space()
    {
        $space = factory(ExchangeSpace::class)->states('not-inquiry-or-completed')->create();
        $this->signIn($space->listing->user);

        $this->delete(route('listing.destroy', ['id' => $space->listing->id, 'space_id' => $space->id]));

        $this->assertFalse($space->fresh()->useListingClosed());
    }

    /**
    * @test
    */
    public function it_selects_the_correct_email_message_when_a_listing_was_deleted_with_a_completed_exchange_space()
    {
        $listing = factory(Listing::class)->states('published')->create();
        $space1 = factory(ExchangeSpace::class)->states('completed')->create([
            'listing_id' => $listing->id,
        ]);
        $space2 = factory(ExchangeSpace::class)->states('completed')->create([
            'listing_id' => $listing->id,
        ]);

        $this->signIn($listing->user);
        $this->delete(route('listing.destroy', ['id' => $space1->listing->id, 'space_id' => $space1->id]));

        $this->assertEquals($listing->fresh()->deleted_by_space_id, $space1->id);
        $this->assertFalse($space1->fresh()->useListingClosed());
        $this->assertTrue($space2->fresh()->useListingClosed());
    }

    /**
    * @test
    */
    public function it_selects_the_correct_email_message_when_an_exchange_space_deleted_without_a_completed_exchange_space()
    {
        $space = factory(ExchangeSpace::class)->states('not-inquiry-or-completed')->create();
        $space->delete();

        $this->assertTrue($space->fresh()->useListingClosed());
    }

    /**
    * @test
    */
    public function it_selects_the_correct_email_message_when_an_incomplete_exchange_space_deleted_with_a_completed_exchange_space()
    {
        $completedSpace = factory(ExchangeSpace::class)->states('completed')->create();
        $space = factory(ExchangeSpace::class)->states('not-inquiry-or-completed')->create([
            'listing_id' => $completedSpace->listing->id,
        ]);
        $space->delete();

        $this->assertTrue($space->fresh()->useListingClosed());
    }

    /**
    * @test
    */
    public function it_selects_the_correct_email_message_when_a_complete_exchange_space_is_deleted()
    {
        $space = factory(ExchangeSpace::class)->states('completed')->create();
        $space->delete();

        $this->assertTrue($space->fresh()->useListingClosed());
    }
}
