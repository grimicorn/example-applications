<?php

namespace App\Http\Controllers;

use App\Domain\Supports\Geocoder;
use Illuminate\Http\Request;

class UserCurrentLocationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'numeric|required',
            'longitude' => 'numeric|required',
        ]);

        $location = $request->user()->getCurrentLocation(
            $request->get('latitude'),
            $request->get('longitude')
        );

        return [
            'data' => [
                'success_message' => 'Location updated successfully!',
                'success' => true,
                'address' => $location->get('address'),
            ],
        ];
    }

    /*
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     //
    // }
}
