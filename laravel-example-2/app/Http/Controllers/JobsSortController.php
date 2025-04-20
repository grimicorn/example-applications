<?php

namespace App\Http\Controllers;

use App\Job;
use Illuminate\Http\Request;

class JobsSortController extends Controller
{
    public function store(Request $request)
    {
        abort_unless(
            $request->user()->can('create', Job::class),
            403
        );

        $toSort = r_collect($request->validate([
            'jobs.*.id' => 'required|numeric|exists:jobs',
            'jobs.*.sort_order' => 'required|numeric|gte:0',
        ]))->get('jobs');


        Job::whereIn('id', $toSort->pluck('id')->toArray())
            ->get()
            ->each(function ($job) use ($toSort) {
                $job->update([
                    'sort_order' => $toSort->where('id', $job->id)->first()->get('sort_order'),
                ]);
            });

        return [
            'data' => [
                'status' => "Jobs sort completed.",
            ],
        ];
    }
}
