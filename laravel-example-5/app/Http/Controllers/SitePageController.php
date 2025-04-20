<?php

namespace App\Http\Controllers;

use App\SitePage;
use Illuminate\Http\Request;

class SitePageController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  SitePage  $page
     * @return \Illuminate\Http\Response
     */
    public function show(SitePage $page)
    {
        $snapshotConfiguration = $page->snapshotConfigurations()->with(['snapshots', 'snapshots.media'])->first();

        return view('pages.show', [
            'page' => $page,
            'baseline' => $snapshotConfiguration->getBaseline(),
            'latest' => $snapshotConfiguration->getLatestSnapshot(),
        ]);
    }
}
