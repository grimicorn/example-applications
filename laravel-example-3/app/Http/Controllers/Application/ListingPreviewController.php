<?php

namespace App\Http\Controllers\Application;

use App\Listing;
use Illuminate\Http\Request;
use App\Support\HasListingTypes;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Support\Listing\HasControllerHelpers;

class ListingPreviewController extends Controller
{
    use HasListingTypes;
    use HasControllerHelpers;

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $listing = Cache::get($this->cacheKey($id), function () use ($id) {
            return Listing::findOrFail($id);
        });

        return view('app.sections.listing.preview.show', [
            'pageTitle' => 'Businesses',
            'pageSubtitle' => 'Preview',
            'business' => $listing,
            'section' => 'listings',
            'disableSidebar' => true,
            'isPreview' => true,
            'slides' => $listing->getSlides(),
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
        $this->clearPreviewCache($id);

        Cache::rememberForever($this->cacheKey($id), function () use ($id) {
            return $this->fillPreviewListing($id);
        });
    }
}
