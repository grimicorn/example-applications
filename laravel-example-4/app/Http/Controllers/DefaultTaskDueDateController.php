<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class DefaultTaskDueDateController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = User::findOrFail(\Auth::id());

        $user->default_due_date_offset = $this->dueDateOffset($request);
        $user->default_due_date_time = $this->dueDateTime($request);
        $user->default_due_date_enabled = (bool) $request->input('due_date_enabled');
        $user->save();

        return back()->with([
            'alert_success' => 'Default due date updated successfully!',
        ]);
    }

    /**
     * Build the due date offset from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function dueDateOffset(Request $request)
    {
        if ('1' !== $request->input('due_date_enabled')) {
            return '0';
        }

        return (string) intval($request->input('due_date_offset_days'));
    }

    /**
     * Build the due date time from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function dueDateTime(Request $request)
    {
        if ('1' !== $request->input('due_date_enabled')) {
            return '09:00pm';
        }

        // Standardize hour
        $hour = intval($request->input('due_date_hour'));
        $hour = ($hour > 59) ? 59 : $hour;
        $hour = ($hour < 0) ? 0 : $hour;
        $hour = ($hour > 9) ? $hour : "0{$hour}";

        // Standardize minute
        $minute = intval($request->input('due_date_minute'));
        $minute = ($minute > 59) ? 59 : $minute;
        $minute = ($minute < 0) ? 0 : $minute;
        $minute = ($minute > 9) ? $minute : "0{$minute}";

        // Standardize period.
        $period = $request->input('due_date_period');
        $period = ($period  === 'pm') ? 'pm' : 'am';

        return "{$hour}:{$minute}{$period}";
    }
}
