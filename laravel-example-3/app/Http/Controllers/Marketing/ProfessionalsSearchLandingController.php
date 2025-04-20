<?php

namespace App\Http\Controllers\Marketing;

use App\Support\User\Search;
use Illuminate\Http\Request;
use App\Marketing\HasSiteData;
use App\Support\HasOccupations;
use App\Http\Controllers\Controller;

class ProfessionalsSearchLandingController extends Controller
{
    use HasSiteData, HasOccupations;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $siteData = $this->getSiteData(
            $view = 'professionals.search-landing',
            $title = 'Search the Professional Directory on Firm Exchange'
        );
        $siteData['occupations' ] = $this->convertForSelect($this->getUserOccupations(), $setValues = true);
        $siteData['professionals'] = (new Search($request->all()))
                                                    ->execute();

        return view('marketing.professionals.search-landing', $siteData);
    }
}
