<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Marketing\HasSiteData;

class ExternalLinkSpeedBumpController extends Controller
{
    use HasSiteData;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $siteData = $this->getSiteData($view = 'marketing.speed-bump-external', $title = 'External Link');
        $siteData['link'] = request()->get('link');
        return view($view, $siteData);
    }
}
