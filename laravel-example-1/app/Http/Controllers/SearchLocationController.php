<?php

namespace App\Http\Controllers;

use App\Models\SearchLocation;
use Illuminate\Http\Request;

class SearchLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     //
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string',
            'address' => 'required|string',
        ]);

        $location = SearchLocation::firstOrCreate([
            'user_id' => $request->user()->id,
            'address' => $validated['address'],
        ], [
            'name' => $validated['name'] ?? $validated['address'],
        ]);

        return [
            'success_message' => 'Search location saved successfully.',
            'location' => $location,
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SearchLocation  $searchLocation
     * @return \Illuminate\Http\Response
     */
    // public function show(SearchLocation $searchLocation)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SearchLocation  $searchLocation
     * @return \Illuminate\Http\Response
     */
    // public function edit(SearchLocation $searchLocation)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SearchLocation  $searchLocation
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, SearchLocation $searchLocation)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SearchLocation  $searchLocation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, SearchLocation $searchLocation)
    {
        abort_if($searchLocation->user_id !== $request->user()->id, 404);

        $searchLocation->delete();

        return [
            'success_message' => 'Search location removed successfully.'
        ];
    }
}
