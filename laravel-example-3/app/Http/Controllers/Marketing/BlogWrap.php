<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use App\Marketing\HasSiteData;
use App\Http\Controllers\Controller;

class BlogWrap extends Controller
{
    use HasSiteData;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $siteData = $this->getSiteData($view = 'news', $title = 'News');
        $header = view('marketing.partials.header.header', $siteData)->render();
        $footer = view('marketing.partials.footer.footer', $siteData)->render();

        return [
            'header' => $this->formatForJSON($header),
            'footer' => $this->formatForJSON($footer),
            'scripts' => '',
            'styles' => url(mix('css/blog-wrap.css')->toHtml()),
        ];
    }

    /**
     * Formats the view for JSON transport.
     *
     * @param  string $view
     *
     * @return string
     */
    protected function formatForJSON($view)
    {
        return collect(explode("\n", $view))
                ->map(function($value) {
                    return trim($value);
                })
                ->filter()
                ->implode(' ');
    }
}
