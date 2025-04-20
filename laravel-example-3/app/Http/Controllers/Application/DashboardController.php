<?php

namespace App\Http\Controllers\Application;

use App\SystemMessage;
use Carbon\Carbon;
use App\ExchangeSpace;
use App\ExchangeSpaceMember;
use Illuminate\Http\Request;
use App\ExchangeSpaceNotification;
use App\Http\Controllers\Controller;
use App\Support\ExchangeSpaceDealType;
use App\Support\Notification\NotificationType;
use App\Support\Notification\HasNotifications;

class DashboardController extends Controller
{
    use HasNotifications;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');

        // $this->middleware('subscribed');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function show()
    {
        $spaceIds = ExchangeSpaceMember::ofCurrentUser()
        ->onDashboard()->active()->get()->pluck('exchange_space_id')->toArray();

        return view('app.sections.dashboard.show', [
            'pageTitle' => 'Dashboard',
            'section' => 'dashboard',
            'spaces' => ExchangeSpace::whereIn('id', $spaceIds)->get()->take(7),
            'notifications' => $this->getUserNotifications(7),
            'systemMessages' => SystemMessage::active()->get(),
            'tourUrl' => '/tours/dashboard',
            'tourEnabled' => false,
            'tourActivateLink' => route('dashboard', ['tour' => 1]),
        ]);
    }

    /**
     * Redirects and left over /home URLs to the dashboard.
     *
     * @return Response
     */
    public function redirectHome()
    {
        return redirect()->route('dashboard');
    }
}
