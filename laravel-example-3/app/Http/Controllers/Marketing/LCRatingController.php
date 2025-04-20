<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use App\Marketing\HasSiteData;
use App\Http\Controllers\Controller;

class LCRatingController extends Controller
{
    use HasSiteData;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->getSiteData($view = 'lc-rating', $title = 'Listing Completion (LC) Rating');

        return view('marketing.lc-rating', $data);
    }
}
