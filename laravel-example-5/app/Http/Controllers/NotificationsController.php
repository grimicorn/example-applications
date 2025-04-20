<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return [
            'notifications' => request()->user()->notifications()->paginate(
                request()->get('per_page', 10)
            ),
            'unread_total' => request()->user()->notifications()->whereNull('read_at')->count(),
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        $this->authorize('delete', $notification);

        $notification->delete();

        back();
    }
}
