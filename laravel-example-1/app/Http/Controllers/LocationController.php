<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Http\Requests\LocationIndexRequest;
use App\Http\Requests\LocationCreateUpdateRequest;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\LocationIndexRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(LocationIndexRequest $request)
    {
        return $request->filter();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('locations.create', [
            'location' => $location = new Location,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LocationCreateUpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocationCreateUpdateRequest $request)
    {
        return $request->persist();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        return view('locations.show', ['location' => $location]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        return view('locations.edit', [
            'location' => $location->load(['links', 'tags']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LocationCreateUpdateRequest  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(LocationCreateUpdateRequest $request, Location $location)
    {
        return $request->persist();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        $name = $location->name;
        $location->delete();

        return redirect(route('locations.index'))->with([
            'success_message' => "Location \"{$name}\" deleted successfully!",
        ]);
    }
}
