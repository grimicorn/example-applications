<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use App\Marketing\HasSiteData;
use App\Http\Controllers\Controller;

class SiteController extends Controller
{
    use HasSiteData;

    /**
     * Show the home page.
     *
     * @return Response
     */
    public function index()
    {
        $siteData = $this->getSiteData($view = 'home', $title = 'The Best Way to Buy or Sell a Business Online');

        return view('marketing.home', array_merge($siteData, $this->getHomeData()));
    }

    /**
     * Shows a view.
     * Since we are going to have quite a few pages that do not need any data this
     * will map routes to views in the /resources/views/marketing directory.
     * Three segments have already been added if you need more add an optional
     * route segment such as {segment4?} in routes/webp.php.
     *
     * @return Response
     */
    public function show(...$segments)
    {
        $view = collect($segments)->filter()->prepend('marketing')->implode('.');

        if (\View::exists($view)) {
            return view($view, $this->getSiteData($view));
        }

        abort(404);
    }

    /**
     * Redirects default /terms route.
     *
     * @return Response
     */
    public function redirectTerms()
    {
        return redirect('/terms-conditions');
    }
}
