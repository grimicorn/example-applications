<?php

namespace Tests\Feature\Application\LCS;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class LCSCustomPenaltyControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_stores_the_listing_completion_score_custom_penalty()
    {
        $this->signInDeveloperWithEvents();

        $lcs = factory('App\ListingCompletionScoreTotal')->create([
            'custom_penalty' => 0,
        ]);
        $this->assertEquals(0, $lcs->custom_penalty);

        $response = $this->post(route('lcs-custom-penalty.update', ['id' => $lcs->listing->id]), [
            'custom_penalty' => $custom_penalty = 10,
        ]);

        $response->assertStatus(302);
        $this->assertEquals($custom_penalty, $lcs->fresh()->custom_penalty);
    }

    /**
    * @test
    * @group failing
    */
    public function it_limits_listing_completion_score_custom_penalty_maximum_to_100()
    {
        $this->signInDeveloperWithEvents();

        $lcs = factory('App\ListingCompletionScoreTotal')->create([
            'custom_penalty' => 0,
        ]);
        $this->assertEquals(0, $lcs->custom_penalty);

        $response = $this->post(route('lcs-custom-penalty.update', ['id' => $lcs->listing->id]), [
            'custom_penalty' => 101,
        ]);

        $response->assertStatus(302);
        $this->assertEquals(100, $lcs->fresh()->custom_penalty);
    }

    /**
    * @test
    * @group failing
    */
    public function it_limits_listing_completion_score_custom_penalty_minimum_to_0()
    {
        $this->signInDeveloperWithEvents();

        $lcs = factory('App\ListingCompletionScoreTotal')->create([
            'custom_penalty' => 0,
        ]);
        $this->assertEquals(0, $lcs->custom_penalty);

        $response = $this->post(route('lcs-custom-penalty.update', ['id' => $lcs->listing->id]), [
            'custom_penalty' => -1,
        ]);

        $response->assertStatus(302);
        $this->assertEquals(0, $lcs->fresh()->custom_penalty);
    }
}
