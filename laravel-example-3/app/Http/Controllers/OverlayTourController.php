<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\OverlayTours\OverlayTour;

class OverlayTourController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        // For instructions on adding a new tour item see the class
        // comment in App\Support\OverlayTours\OverlayTour
        return (new OverlayTour($slug))->getTourSteps();
    }
}
