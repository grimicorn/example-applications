<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DefaultTaskDueDateControllerTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware;

    /**
     * @test
     */
    public function it_adds_a_default_task_due_date()
    {
        // Setup
        $user = factory(App\User::class)->create();

        Auth::login($user, true);

        // Execute
        $response = $this->patch('/default-task-due-date', [
            'due_date_enabled' => '1',
            'due_date_offset_days' => '2',
            'due_date_hour' => '5',
            'due_date_minute' => '22',
            'due_date_period' => 'am',
        ]);

        // Assert
        $user = $user->fresh();
        $this->assertEquals(302, $response->status());
        $this->assertNotNull(session('alert_success'));
        $this->assertEquals('2', $user->default_due_date_offset);
        $this->assertEquals('05:22am', $user->default_due_date_time);
        $this->assertTrue($user->default_due_date_enabled);
    }

    /**
     * @test
     */
    public function it_removes_a_default_task_due_date()
    {
        // Setup
        $user = factory(App\User::class)->create();

        Auth::login($user, true);

        // Execute
        $response = $this->patch('/default-task-due-date', [
            'due_date_offset_days' => '0',
            'due_date_hour' => '9',
            'due_date_minute' => '00',
            'due_date_period' => 'pm',
        ]);

        // Assert
        $user = $user->fresh();
        $this->assertEquals(302, $response->status());
        $this->assertNotNull(session('alert_success'));
        $this->assertEquals('0', $user->default_due_date_offset);
        $this->assertEquals('09:00pm', $user->default_due_date_time);
        $this->assertFalse($user->default_due_date_enabled);
    }
}
