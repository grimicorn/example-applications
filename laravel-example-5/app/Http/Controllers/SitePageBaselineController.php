<?php

namespace App\Http\Controllers;

use App\Snapshot;
use Illuminate\Http\Request;

class SitePageBaselineController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Snapshot  $snapshot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Snapshot $snapshot)
    {
        $this->authorize('update', $snapshot->configuration);

        $snapshot->baseline()->get()->each->delete();
        $snapshot->setBaseline();

        // We want to make sure we have a new screenshot
        $snapshot->configuration->updateSnapshot();

        $snapshot->page->setNeedsReviewStatus();

        $status = 'Baseline updated successfully!';

        if ($request->expectsJson()) {
            return [
                'status' => $status,
            ];
        }

        return back()->with('status', $status);
    }
}
