<?php

namespace App\Http\Controllers\Application;

use App\ExchangeSpace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Support\Notification\ReportsAbuse;
use App\Support\Notification\NotificationType;

class ExchangeSpaceClosedController extends Controller
{
    use ReportsAbuse;

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $space = ExchangeSpace::withTrashed()->findOrFail($id);
        $listing = optional($space->listing()->withTrashed()->get()->first());
        $rejected = $space->isRejected();
        $title = $rejected ? 'Business inquiry Closed' : 'Exchange Space Closed';

        return  view('app.sections.exchange-space.closed.show', [
            'rejected' => $rejected,
            'rejection' => optional($space->rejectionReason),
            'space' => $space,
            'pageTitle' => $title,
            'pageSubtitle' => $rejected ? null : $space->title,
            'section' => $rejected ? 'business-inquires' : 'exchange-spaces',
            'disablePageNav' => true,
            'use_listing_closed' => $space->useListingClosed(),
            'participant_message' => $message = $listing->participantMessage($space),
            'pageSubSubtitle' => "Business: {$space->listing->title}",
            'pageSubSubtitleLink' => route('businesses.show', ['id' => $space->listing->id]),
            'watchlists_url' => route('watchlists.index'),
            'listings_search_url' => route('businesses.search-landing'),
            'report_abuse_link' => $this->reportAbuseLink(
                $message,
                auth()->id(),
                $listing->user->id,
                NotificationType::DELETED_EXCHANGE_SPACE
            ),
            'is_default_message' => $message === $listing->default_participant_message,
            'listing_title' => $listing->title,
            'buyer_has_left' => $space->buyerHasLeft(),
        ]);
    }
}
