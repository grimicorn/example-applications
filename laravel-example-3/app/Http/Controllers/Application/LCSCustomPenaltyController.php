<?php

namespace App\Http\Controllers\Application;

use App\Listing;
use Illuminate\Http\Request;
use App\Support\HasResponse;
use App\Http\Controllers\Controller;

class LCSCustomPenaltyController extends Controller
{
    use HasResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->has('listing_id')) {
            return redirect(route('lcs-custom-penalty.edit', [
                'id' => request()->get('listing_id')
            ]));
        }

        return view('app.sections.admin.lcs-custom-penalty.index', [
            'pageTitle' => 'Admin',
            'pageSubtitle' => 'LCS Custom Penalty',
            'section' => 'admin',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('app.sections.admin.lcs-custom-penalty.edit', [
            'listing' => $listing = Listing::find($id),
            'id' => $id,
            'pageTitle' => $listing ? $listing->title : 'Not Found',
            'pageSubtitle' => 'LCS Custom Penalty',
            'section' => 'admin',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'custom_penalty' => 'required',
        ]);

        $lcs = Listing::findOrFail($id)->listingCompletionScoreTotal;
        $custom_penalty = intval($request->get('custom_penalty'));
        $custom_penalty = ($custom_penalty > 100) ? 100 : $custom_penalty;
        $custom_penalty = ($custom_penalty < 0) ? 0 : $custom_penalty;
        $lcs->custom_penalty = $custom_penalty;
        $lcs->save();

        return $this->successResponse('Custom penalty saved successfully', $request);
    }
}
