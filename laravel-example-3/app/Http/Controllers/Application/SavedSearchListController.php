<?php

namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SavedSearch;

class SavedSearchListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return [
            'searches' => SavedSearch::where('user_id', auth()->id())->get(),
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return [
            'search' => SavedSearch::where('user_id', auth()->id())->where('id', $id)->first(),
        ];
    }
}
