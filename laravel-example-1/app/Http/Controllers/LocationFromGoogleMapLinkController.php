<?php

namespace App\Http\Controllers;

use App\Domain\Supports\GoogleMapLinkToLocation;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationFromGoogleMapLinkController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('location-from-google-maps.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'link' => 'required|string|unique:locations,google_maps_link',
        ]);

        $location = (new GoogleMapLinkToLocation)->firstOrCreate($request->get('link'));

        return redirect(route('locations.edit', ['location' => $location]))->with([
            'success_message' => "Location \"{$location->name}\" created successfully!",
        ]);
    }
}
