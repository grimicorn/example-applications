<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NewTaskControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_authenicates_the_user_with_a_supplied_valid_api_token()
    {
        $api_token = 'ba4c7107aa2044a393e6c625c61795b6c91cef16';
        $user = factory(App\User::class)->create(compact('api_token'));

        $response = $this->json('POST', 'api/task/new', [
                        'token' => $api_token,
                        'item' => 'Test Item',
                        'list' => 'Test List',
                    ]);

        $this->assertEquals(200, $response->status());
    }

    /**
     * @test
     */
    public function it_requires_list_parameter_to_add_a_new_item()
    {
        $api_token = 'ba4c7107aa2044a393e6c625c61795b6c91cef16';
        $user = factory(App\User::class)->create(compact('api_token'));

        $response = $this->json('POST', 'api/task/new', [
                        'token' => $api_token,
                        'item' => 'Test Item',
                    ]);

        $this->assertEquals(422, $response->status());
    }

    /**
     * @test
     */
    public function it_requires_item_parameter_to_add_a_new_item()
    {
        $api_token = 'ba4c7107aa2044a393e6c625c61795b6c91cef16';
        $user = factory(App\User::class)->create(compact('api_token'));

        $response = $this->json('POST', 'api/task/new', [
                        'token' => $api_token,
                        'list' => 'Test List',
                    ]);

        $this->assertEquals(422, $response->status());
    }

    /**
     * @test
     */
    public function it_disables_access_with_invalid_access_token()
    {
        $response = $this->json('POST', 'api/task/new', [ 'token' => 'invalidtoken' ]);

        $this->assertEquals(401, $response->status());
    }
}
