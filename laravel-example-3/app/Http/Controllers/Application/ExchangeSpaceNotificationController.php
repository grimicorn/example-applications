<?php

namespace App\Http\Controllers\Application;

use App\ExchangeSpace;
use App\Support\Notification\HasNotifications;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExchangeSpaceNotificationController extends Controller
{
    use HasNotifications;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $space = ExchangeSpace::findOrFail($id);
        $notifications = $this->getUserNotificationsForSpaceAndConversations($space);
        $pageTitle = 'All Exchange Space Notifications';

        if (request()->has('read')) {
            $notifications = $notifications->where('read', true);
            $pageTitle = 'Read Exchange Space Notifications';
        } elseif (request()->has('unread')) {
            $notifications = $notifications->where('read', false);
            $pageTitle = 'Unread Exchange Space Notifications';
        }

        return view('app.sections.exchange-space.notifications.index', [
            'pageTitle' => $pageTitle,
            'section' => 'exchange-space',
            'space' => $space,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
