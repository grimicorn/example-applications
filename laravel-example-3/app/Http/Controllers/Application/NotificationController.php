<?php

namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\Support\HasResponse;
use App\Http\Controllers\Controller;
use App\Support\Notification\HasNotifications;
use App\ExchangeSpaceNotification;

class NotificationController extends Controller
{
    use HasNotifications;
    use HasResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('app.sections.notifications.index', [
            'pageTitle' => 'All Notifications',
            'section' => 'notifications',
            'notifications' => $this->getUserNotifications(),
        ]);
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
        $request->validate([
            'type' => 'required',
        ]);

        // Get the notification.
        $notification = $this->getNotificationByType($id, intval($request->get('type')));

        // Make sure the user can edit the notification.
        if (intval($notification->user_id) !== intval(auth()->id())) {
            abort(403, 'Forbidden');
        }

        // Update the notification.
        $notification->read = (bool) $request->get('read', false);
        $notification->save();

        return $this->successResponse(
            'Notification updated successfully!',
            $request
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $request->validate([
            'type' => 'required',
        ]);

        // Get the notification.
        $notification = $this->getNotificationByType($id, intval($request->get('type')));
        $notification->delete();
    }
}
