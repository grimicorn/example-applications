<?php

namespace App\Http\Controllers;

use App\SitePage;
use Illuminate\Http\Request;

class SitePageDifferenceThresholdController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate(['difference_threshold' => 'required|numeric']);
        $page = SitePage::findOrFail($id);

        $this->authorize('update', $page);

        $page->fill($data);
        $page->save();

        $page->fresh()->setNeedsReviewStatus();

        $status = 'Page difference threshold updated successfully';
        if ($request->expectsJson()) {
            return [
                'status' => $status,
            ];
        }

        return back()->with('status', $status);
    }
}
