<?php

namespace App\Http\Controllers\Application;

use App\Listing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Support\Listing\HasControllerHelpers;
use App\Support\HistoricalFinancial\PreviewTableRows;

class ListingHistoricalFinancialsPreviewController extends Controller
{
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

        return view('app.sections.listing.preview.historical-financials.show', [
            'pageTitle' => 'Businesses',
            'pageSubtitle' => 'Historical Financials Preview',
            'business' => $listing,
            'section' => 'listings',
            'disableSidebar' => true,
            'isPreview' => true,
            'rows' => (new PreviewTableRows($listing))->getHistoricalFinancials(),
        ]);
    }
}
