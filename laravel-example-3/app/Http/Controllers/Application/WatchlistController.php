<?php

namespace App\Http\Controllers\Application;

use App\SavedSearch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WatchlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // The table expects a paginated set of results.
        // We are not paginating theses so we have to
        // fake it until we make it.
        $watchlists = auth()->user()->savedSearches
                      ->sortByDesc('updated_at')->values();
        $watchlists = $watchlists->paginate(
            ($watchlists->count() <= 0) ? 1 : $watchlists->count()
        );

        return view('app.sections.watchlist.index', [
            'pageTitle' => 'Watch lists',
            'columns' => [
                [
                    'label' => 'Search Name',
                    'sortDisabled' => true,
                    'class' => 'wl-name',
                ],

                [
                    'label' => 'Matches',
                    'sortDisabled' => true,
                    'class' => 'wl-matches',
                ],

                [
                    'label' => 'Actions',
                    'sortDisabled' => true,
                    'class' => 'wl-actions',
                ],
            ],
            'paginatedWatchlists' => $watchlists,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $watchlist = SavedSearch::findOrFail($id);
        return view('app.sections.watchlist.show', [
            'pageTitle' => 'Watch lists',
            'pageSubtitle' => $watchlist->name,
            'watchlist' => $watchlist,
            'matches' => $watchlist->sortedMatches(25),
        ]);
    }
}
