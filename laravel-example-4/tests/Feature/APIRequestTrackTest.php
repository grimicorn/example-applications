<?php

namespace Tests\Feature;

use App\User;
use Carbon\Carbon;
use App\RequestLog;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class APIRequestTrackTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_tracks_a_users_api_requests_in_the_current_month()
    {
        // Setup
        $pastDate = Carbon::now()->subMonths(2);
        $currentDate = Carbon::now();
        $api_token = 'ba4c7107aa2044a393e6c625c61795b6c91cef16';
        $user = factory(User::class)->create(compact('api_token'));
        factory(RequestLog::class)->create([
            'user_id' => $user->id,
            'count' => 10,
            'month' => $pastDate->format('n'),
            'year' => $pastDate->format('Y'),
        ]);
        factory(RequestLog::class)->create([
            'user_id' => $user->id,
            'count' => 14,
            'month' => $currentDate->format('n'),
            'year' => $currentDate->format('Y'),
        ]);

        // Execute
        $this->json('POST', 'api/task/new', ['token' => $api_token, 'item' => 'Test Item 1', 'list' => 'Test List']);
        $response = $this->json('POST', 'api/task/new', ['token' => $api_token, 'item' => 'Test Item 2', 'list' => 'Test List']);

        // Assert
        $user = $user->fresh();
        $latestLog = $user->requestLog
                     ->where('month', $currentDate->format('n'))
                     ->where('year', $currentDate->format('Y'))
                     ->first();
        $this->assertEquals(16, $latestLog->count);
    }
}
