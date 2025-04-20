<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SelectListControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_selects_a_list()
    {
        // Setup
        $list = 'Test List';
        $user = factory(App\User::class)->create();
        Auth::login($user, true);

        // Execute
        $response = $this->patch('select-list', ['selected_list' => $list]);

        // Assert
        $this->assertEquals(302, $response->status());
        $this->withSession(['alert_success', 'List selected successfully!']);
        $this->assertEquals($user->fresh()->selected_list, $list);
    }

    /**
     * @test
     */
    public function it_requires_a_selected_list()
    {
        // Setup
        $user = factory(App\User::class)->create();
        Auth::login($user, true);

        // Execute
        $response = $this->patch('select-list', []);

        // Assert
        $this->assertEquals(302, $response->status());
        $this->withSession(['error', 'The selected list field is required.']);
    }

    /**
     * @test
     */
    public function it_requires_selected_list_to_be_filled()
    {
        // Setup
        $user = factory(App\User::class)->create();
        Auth::login($user, true);

        // Execute
        $response = $this->patch('select-list', ['selected_list' => '']);

        // Assert
        $this->assertEquals(302, $response->status());
        $this->withSession(['error', 'The selected list field is required.']);
    }
}
