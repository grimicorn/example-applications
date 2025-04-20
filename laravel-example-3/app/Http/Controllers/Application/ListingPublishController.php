<?php

namespace App\Http\Controllers\Application;

use App\Listing;
use Illuminate\Http\Request;
use App\Support\HasResponse;
use App\Support\HasStripeHelpers;
use App\Http\Controllers\Controller;
use App\Support\Listing\HasControllerHelpers;

class ListingPublishController extends Controller
{
    use HasResponse;
    use HasStripeHelpers;
    use HasControllerHelpers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('listing-owner');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $listing = Listing::findOrFail($id);
        $user = auth()->user();

        // If the user is not subscribded then they will need to process the payment
        // unless the listing has already been paid for individually.
        if (!$user->isSubscribed() and !$listing->isRepublishable()) {
            $pricing = $this->getPerListingPricing();
            $price = optional($pricing)->get('amount');
            $title = optional($pricing)->get('name');

            if (is_null($price)) {
                abort(422, "{$title} price does not exist.");
            }

            // Process the payment
            $user->setStripeKey(config('services.stripe.secret'));
            $invoice = $user->invoiceFor($title, $this->convertPriceForStripe($price));
            if (!$invoice) {
                abort(422, 'There was a problem with your payment please try again.');
            }

            // Set the listings invoice provider id.
            $listing->invoice_provider_id = $invoice->id;
        }

        // Publish the listing.
        $listing->published = true;
        $listing->should_display_encouragement_modal = false;
        $listing->save();
        $this->clearPreviewCache($listing->id);

        return $this->successResponse('Business page activated successfully!', $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $listing = Listing::findOrFail($id);
        $listing->published = false;
        $listing->save();
        $this->clearPreviewCache($listing->id);


        return $this->successResponse('Business page deactivated successfully!', $request);
    }
}
