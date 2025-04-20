<?php

namespace App\Sync;

use App\User;
use Carbon\Carbon;
use App\RequestLog;

trait APIRequestTrack
{
    /**
     * Logs an API request
     *
     * @return int
     */
    protected function logAPIRequestCount()
    {
        $user_id = \Auth::id();
        $date = Carbon::now();
        $month = $date->format('n');
        $year = $date->format('Y');

        $log = RequestLog::firstOrNew(compact('user_id', 'month', 'year'));
        $log->count = intval($log->count) + 1;
        $log->save();

        return $log->count;
    }
}
