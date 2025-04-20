<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;

class NotificationReadController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        $this->authorize('update', $notification);

        $notification->markAsRead();

        return $this->handleRedirect($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        $this->authorize('update', $notification);

        $notification->markAsUnread();

        return $this->handleRedirect($notification);
    }

    protected function handleRedirect(Notification $notification)
    {
        if (!$notification->link) {
            return back();
        }

        if (request()->expectsJson()) {
            return ['redirect' => $notification->link];
        }

        return redirect($notification->link);
    }
}
