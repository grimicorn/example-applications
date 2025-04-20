<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use App\Marketing\HasSiteData;
use App\Http\Controllers\Controller;

class PricingController extends Controller
{
    use HasSiteData;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->getSiteData($view = 'pricing', $title = 'Pricing');

        return view('marketing.pricing', $data);
    }
}
