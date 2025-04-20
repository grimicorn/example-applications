<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use App\Marketing\HasSiteData;
use App\Support\Listing\Search;
use App\Http\Controllers\Controller;
use App\BusinessCategory;

class BusinessesSearchLandingController extends Controller
{
    use HasSiteData;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $siteData = $this->getSiteData(
            $view = 'businesses.search-landing',
            $title = 'Search Businesses for Sale'
        );

        $categories = BusinessCategory::orderBy('label')->get();
        $chunk = round($categories->count()/4);
        $siteData['categoryColumns'] = collect([
            $column1 = $categories->splice(0, $chunk),
            $column2 = $categories->splice(0, $chunk),
            $column3 = $categories->splice(0, $chunk),
            $column4 = $categories->splice(0),
        ]);

        return view('marketing.businesses.search-landing', $siteData);
    }
}
