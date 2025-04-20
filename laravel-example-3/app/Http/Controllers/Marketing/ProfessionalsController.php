<?php

namespace App\Http\Controllers\Marketing;

use App\User;
use App\Support\User\Search;
use Illuminate\Http\Request;
use App\Marketing\HasSiteData;
use App\Support\HasOccupations;
use App\Support\HasSearchInputs;
use App\Http\Controllers\Controller;

class ProfessionalsController extends Controller
{
    use HasSiteData;
    use HasOccupations;
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
            'professionals.search.inputs'
        );

        $siteData = $this->getSiteData($view = 'professionals.index', $title = 'Professional Search Results');
        $siteData['occupations' ] = $this->convertForSelect($this->getUserOccupations(), $setValues = true);
        $siteData['professionals'] = (new Search($inputs))->execute();

        return view('marketing.professionals.index', $siteData);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $siteData = $this->getSiteData($view = 'professionals.show', $title = 'User Profile');
        $siteData['professional'] = $user = User::with('professionalInformation')
                                                  ->with('desiredPurchaseCriteria')
                                                  ->findOrFail($id);
        $siteData['myListings'] = $user->myListings();

        return view('marketing.professionals.show', $siteData);
    }
}
