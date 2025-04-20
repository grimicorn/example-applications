<?php

namespace App\Http\Controllers\Application;

use App\Listing;
use Laravel\Spark\Spark;
use App\Support\HasResponse;
use Illuminate\Http\Request;
use App\Support\HasListingTypes;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\HasBusinessCategories;
use App\Support\Listing\HasControllerHelpers;
use App\Support\TableFilters\ListingTableFilter;
use App\Support\Listing\Documents as ListingDocuments;

class ListingDetailsController extends Controller
{
    use HasListingTypes;
    use HasBusinessCategories;
    use HasResponse;
    use HasControllerHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $listings = (new ListingTableFilter($request))->paginated();

        // If we want JSON it is because we are sorting the listings.
        if ($request->expectsJson()) {
            return $listings;
        }

        return view('app.sections.listing.index', [
            'pageTitle' => 'Businesses',
            'section' => 'listings',
            'paginatedListings' => $listings,
            'columns' => [
                [ 'label' => 'Business Name' ],

                [
                    'label' => 'Date Added',
                    'isSorted' => true,
                    'sortOrder' => 'desc',
                    'sortKey' => 'created_at',
                ],

                [
                    'label' => 'Status',
                    'sortKey' => 'published',
                ],

                [
                    'label' => '',
                    'sortDisabled' => true,
                ],

                [
                    'label' => '',
                    'sortDisabled' => true,
                ],
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Make up an initial listing
        $listing = new Listing;
        $listing->user_id = Auth::id();
        $listing->id = 0;
        foreach ($listing->getFillable() as $key) {
            $listing->$key = null;
        }

        $sharedData = $this->sharedShowFormData($listing, '');
        $sharedData['action'] = route('listing.create');
        $sharedData['method'] = 'POST';
        $sharedData['formTitle'] = 'Create Business';
        $sharedData['enableRedirect'] = true;
        $sharedData['enablePublish'] = true;
        $sharedData['previewRoutes'] =[
            route('listing.preview', ['id' => $listing->id])
        ];
        $sharedData['tourUrl'] = '/tours/listing-create';
        $sharedData['tourEnabled'] = false;
        $sharedData['tourActivateLink'] = route('listing.create', ['tour' => 1]);
        $sharedData['previewStoreRoute'] = route('listing.preview.update', ['id' => $listing->id]);

        return view('app.sections.listing.details.form', $sharedData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate
        $this->validateSubmission($request);

        // Handle adding the listing data.
        $listing = new Listing;
        $listing->user_id = Auth::id();
        $listing->fill($this->getFields($request));
        $listing->save();

        // Upload the documents if needed.
        (new ListingDocuments($listing))->update($request);

        // Save the listing score
        $listing->fresh()->listingCompletionScore->save();

        // Clear the create cache.
        $this->clearPreviewCache(0);

        return $this->successResponse(
            'Your listing has been successfully saved.',
            $request,
            route(
                'listing.details.edit',
                array_merge(
                    ['id' => $listing->id],
                    array_filter(['enable_publish_modal' => request()->get('enable_publish_modal')])
                )
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->checkAccess($listing = Listing::findOrFail($id));

        $sharedData = $this->sharedShowFormData($listing, '');
        $sharedData['action'] = route('listing.details.update', ['id' => $listing->id]);
        $sharedData['method'] = 'PATCH';
        $sharedData['formTitle'] = 'Edit Business';
        $sharedData['enablePublish'] = true;
        $sharedData['enablePublishModal'] = (request()->get('enable_publish_modal') and !$listing->published);
        $sharedData['previewRoutes'] =[
            route('listing.preview', ['id' => $listing->id])
        ];
        $sharedData['tourUrl'] = '/tours/listing-edit';
        $sharedData['tourEnabled'] = false;
        $sharedData['tourActivateLink'] = route('listing.details.edit', ['id' => $listing->id, 'tour' => 1]);
        $sharedData['previewStoreRoute'] = route('listing.preview.update', ['id' => $listing->id]);

        $historicalFinancial = $listing->listingCompletionScore->historicalFinancialCalculations;
        $generalErrors = [];

        if (!empty($generalErrors)) {
            session()->flash('general-errors', $generalErrors);
        }

        return view('app.sections.listing.details.form', $sharedData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->checkAccess($listing = Listing::findOrFail($id));

        $this->validateSubmission($request);

        $this->clearPreviewCache($id);

        // Update regular listing details.
        $listing = Listing::findOrFail($id);

        $listing->fill($this->getFields($request));
        $listing->save();

        // Upload the documents if needed.
        (new ListingDocuments($listing))->update($request);

        // Save the listing score
        $listing->fresh()->listingCompletionScore->save();

        return $this->successResponse('Your listing has been successfully saved.', $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->checkAccess($listing = Listing::findOrFail($id));

        $listing->listingCompletionScoreTotal()->delete();

        // Handles saving the space id that deleted the listing if specified.
        $listing->deleted_by_space_id = request()->get('space_id');
        $listing->save();

        $listing->delete();

        return $this->successResponse('Your listing has been successfully deleted!', $request, route('listing.index'));
    }

    /**
     * The shared show form listing data.
     * Used for create/edit form.
     *
     * @param  Listing $listing
     *
     * @return array
     */
    protected function sharedShowFormData(Listing $listing, $pageSubTitle)
    {
        return [
            'pageTitle' => 'Businesses',
            'pageSubtitle' => $pageSubTitle,
            'section' => 'listings',
            'listing' => $listing,
            'listingTypes' => $this->getListingTypesForSelect(),
            'businessParentCategories' => $this->getBusinessParentCategoriesForSelect(),
            'businessChildCategories' => $this->getBusinessChildCategoriesForSelect(),
            'financialDetailOptions' => [
                ['label' => 'N/A', 'value' => 1],
                ['label' => 'Included in Asking Price', 'value' => 2],
                ['label' => 'Not Included in Asking Price', 'value' => 3],
            ],
        ];
    }
}
