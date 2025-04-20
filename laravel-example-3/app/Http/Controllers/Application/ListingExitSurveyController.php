<?php

namespace App\Http\Controllers\Application;

use App\Listing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Support\HasResponse;

class ListingExitSurveyController extends Controller
{
    use HasResponse;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $listing = Listing::findOrFail($id);
        $listing->saveExitSurvey();

        return $this->successResponse('Survey submitted successfully', $request);
    }
}
