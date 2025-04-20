<?php

namespace App\Http\Controllers\Application;

use App\Listing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Support\Listing\HasControllerHelpers;

class ListingCompletionScoreController extends Controller
{
    use HasControllerHelpers;

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $listing = Listing::findOrFail($id);
        $score = $listing->listingCompletionScore;
        $businessOverview = $score->businessOverviewCalculations;
        $historicalFinancial = $score->historicalFinancialCalculations;
        $generalErrors = [];

        if (!empty($generalErrors)) {
            session()->flash('general-errors', $generalErrors);
        }

        return view('app.sections.listing.completion-score.show', [
            'pageTitle' => 'Businesses',
            'pageSubtitle' => '',
            'section' => 'listings',
            'listing' => $listing,
            'score' => $score,
            'businessOverview' => $businessOverview,
            'historicalFinancial' => $historicalFinancial,
            'tourUrl' => '/tours/lcs-index',
            'tourEnabled' => false,
            'tourActivateLink' => route('listing.completion-score.index', ['id' => $listing->id, 'tour' => 1]),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create($type, $section, $subsection = null)
    {
        $listing = $this->fillPreviewListing(0);

        // Possible business overview
        // /dashboard/listing/{id}/completion-score/overview/{section}
        // /dashboard/listing/{id}/completion-score/overview/total
        // /dashboard/listing/{}id/completion-score/overview/total-percentage
        // /dashboard/listing/{id}/completion-score/overview/total-possible

        // Possible historical financials
        // /dashboard/listing/{id}/completion-score/overview/{section}
        // /dashboard/listing/{id}/completion-score/overview/total
        // /dashboard/listing/{}id/completion-score/overview/total-percentage
        // /dashboard/listing/{id}/completion-score/overview/total-possible

        return [
            'value' => $listing->listingCompletionScore
                       ->getShowValue($type, $section, $subsection),
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $type, $section, $subsection = null)
    {
        $listing = $this->fillPreviewListing($id);

        // Possible business overview
        // /dashboard/listing/{id}/completion-score/overview/{section}
        // /dashboard/listing/{id}/completion-score/overview/total
        // /dashboard/listing/{}id/completion-score/overview/total-percentage
        // /dashboard/listing/{id}/completion-score/overview/total-possible

        // Possible historical financials
        // /dashboard/listing/{id}/completion-score/overview/{section}
        // /dashboard/listing/{id}/completion-score/overview/total
        // /dashboard/listing/{}id/completion-score/overview/total-percentage
        // /dashboard/listing/{id}/completion-score/overview/total-possible

        return [
            'value' => $listing->listingCompletionScore
                        ->setListing($listing)
                       ->getShowValue($type, $section, $subsection),
        ];
    }
}
