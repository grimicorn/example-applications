<?php

namespace App\Http\Controllers;

use App\Site;
use Illuminate\Http\Request;

class SiteBaselinesController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Site $site)
    {
        $this->authorize('update', $site);

        $site->resetBaselineSnapshots();

        $status = 'Baseline reset queued.';
        if ($request->expectsJson()) {
            return [
                'status' => $status,
            ];
        }

        return back()->with('status', $status);
    }
}
