<?php

namespace App\Http\Controllers\Marketing;

use App\Listing;
use Illuminate\Http\Request;
use App\Marketing\HasSiteData;
use App\Support\Listing\Search;
use App\Support\HasSearchInputs;
use App\Http\Controllers\Controller;
use App\SavedSearch;

class BusinessesSearchController extends Controller
{
    use HasSiteData;
    use HasSearchInputs;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Set the search inputs.
        $inputs = $this->setSearchInputs(
            $request->all(),
            'listing.search.inputs'
        );

        // Set site data.
        $siteData = $this->getSiteData($view = 'businesses.index', $title = 'Search Businesses for Sale on Firm Exchange');
        $siteData['businesses'] = $businesses = (new Search($inputs))->execute();
        $siteData['businessesJson'] = $businesses->toJson();

        return view('marketing.businesses.index', $siteData);
    }

    public function show($id)
    {
        $siteData = $this->getSiteData($view = 'businesses.show', $title = 'Business Overview');
        $siteData['business'] = $business = Listing::findOrFail($id);
        $siteData['slides'] = $business->getSlides();

        return view('marketing.businesses.show', $siteData);
    }
}
