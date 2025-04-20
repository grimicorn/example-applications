<?php

namespace App\Http\Controllers;

use App\Job;
use App\Http\Requests\JobFormRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Domain\Database\QueryBuilder\FiltersControlNumber;
use App\Enums\WipStatus;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(JobFormRequest $request)
    {
        $query = Job::query();

        $jobs = QueryBuilder::for($query)
            ->with('machine', 'machine.pods')
            ->defaultSort('due_at')
            ->allowedSorts([
                'due_at',
                'work_order_number',
                'customer_name',
                'machine_id',
                'priority',
                'flag',
            ])
            ->allowedFilters([
                AllowedFilter::exact('machine_id'),
                'wip_status',
                'work_order_number',
                AllowedFilter::custom('control_number', new FiltersControlNumber),
            ])
            ->paginate(
                config('domain.jobs_per_page')
            );

        if ($request->expectsJson()) {
            return [
                'data' => [
                    'items' => $jobs,
                ],
            ];
        }

        return view('jobs.index', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\JobFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JobFormRequest $request)
    {
        return $request->persist()->respond();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\JobFormRequest  $request
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(JobFormRequest $request, Job $job)
    {
        return $request->persist($job)->respond();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job, JobFormRequest $request)
    {
        return $request->persist($job)->respond();
    }
}
