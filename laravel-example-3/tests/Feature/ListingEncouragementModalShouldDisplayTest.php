<?php

namespace Tests\Feature;

use App\Listing;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListingEncouragementModalShouldDisplayTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_a_listing_owner_can_set_the_encouragement_to_not_display()
    {
        $user = $this->signIn();
        $listing = factory(Listing::class)->create([
            'should_display_encouragement_modal' => true,
        ]);

        $this->delete(route('listing-encouragement-modal-should-display.destroy', ['id' => $listing->id]))
            ->assertStatus(403);

        $this->assertTrue($listing->fresh()->should_display_encouragement_modal);
    }

    /** @test */
    public function it_sets_the_encouragement_to_not_display()
    {
        $listing = factory(Listing::class)->create([
            'should_display_encouragement_modal' => true,
            'user_id' => $this->signIn()->id,
        ]);

        $this->delete(route('listing-encouragement-modal-should-display.destroy', ['id' => $listing->id]))
            ->assertStatus(200);

        $this->assertFalse($listing->fresh()->should_display_encouragement_modal);
    }
}
