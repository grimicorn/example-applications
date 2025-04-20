<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationRatingController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        $request->validate([
            'rating' => 'numeric|min:0|max:5',
        ]);

        $location->rating = floatval($request->get('rating'));
        $location->save();

        if ($request->expectsJson()) {
            return [
                'success_message' => 'Rating updated successfully!'
            ];
        }

        return back();
    }
}
