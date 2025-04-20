<?php

namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Listing;

class ListingRepublishableController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return [
            'republishable' => Listing::findOrFail($id)->isRepublishable(),
        ];
    }
}
