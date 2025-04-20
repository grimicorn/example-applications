<?php

namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardModuleDismissController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $user = auth()->user();
        $user->getting_started_dismissed = true;
        $user->save();

        $status = 'Removed from dashboard successfully.';

        if (request()->expectsJson()) {
            return [
                'status' => $status,
            ];
        }

        return redirect(route('dashboard'))
            ->with('status', $status);
    }
}
