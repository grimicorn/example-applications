<?php

namespace App\Support\Listing;

use App\Listing;
use Laravel\Spark\Spark;
use Illuminate\Http\Request;
use App\Support\PreviewMedia;
use App\Rules\ValidInvalidUrl;
use Illuminate\Validation\Rule;
use App\Support\Listing\Documents;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Support\HistoricalFinancial\YearlyData;
use App\Support\HistoricalFinancial\HasYearlyDataHelpers;

trait HasControllerHelpers
{
    use HasYearlyDataHelpers;

    /**
     * Gets the allowed request fields.
     *
     * @param  Request $request
     *
     * @return array
     */
    protected function getFields(Request $request)
    {
        $fields = (new Listing)->getFillable();
        $fields = $request->only($fields);

        return collect($fields)->map(function ($field) {
            return $field === '' ? null : $field;
        })->toArray();
    }

    /**
     * Validates the form submission.
     */
    protected function validateSubmission(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:200',
            'business_name' => 'required|max:1000',
            'city' => 'nullable|max:1000',
            'state' => 'nullable|max:1000',
            'summary_business_description' => 'nullable|max:1000',
            'business_description' => 'nullable|max:1000',
            'business_category_id' => 'required',
            'business_sub_category_id' => 'nullable',
            'year_established' => 'nullable|date_format:Y',
            'asking_price' => 'numeric|nullable',
            'revenue' => 'nullable|numeric',
            'discretionary_cash_flow' => 'nullable|numeric',
            'pre_tax_earnings' => 'nullable|numeric',
            'ebitda' => 'nullable|numeric',
            'real_estate_estimated' => 'nullable|numeric',
            'real_estate_description' => 'nullable|max:1000',
            'fixtures_equipment_estimated' => 'nullable|numeric',
            'fixtures_equipment_description' => 'nullable|max:1000',
            'inventory_estimated' => 'nullable|numeric',
            'inventory_description' => 'nullable|max:1000',
            'location_description' => 'nullable|max:1000',
            'products_services' => 'nullable|max:1000',
            'market_overview' => 'nullable|max:1000',
            'competitive_advantage' => 'nullable|max:1000',
            'business_performance_outlook' => 'nullable|max:1000',
            'financing_available_description' => 'nullable|max:1000',
            'support_training_description' => 'nullable|max:1000',
            'reason_for_selling' => 'nullable|max:1000',
            'photos' => 'listing_photos_under_limit',
            'links.*' => new ValidInvalidUrl,
            'photos.new.*' => 'nullable|mimes:jpg,jpeg,png|max:4096',
            'files.new.*' => 'nullable|mimes:doc,docx,pdf,xls,xlsx,jpg,jpeg,png,bmp,pptx,ppt|max:5120',
        ]);
    }

    /**
     * Checks if access is allowed.
     *
     * @param  Listing $listing
     */
    protected function checkAccess(Listing $listing)
    {
        // Allow developers to access anything.
        if (Spark::developer(Auth::user()->email)) {
            return;
        }

        // Check all other users for access right.
        if (Auth::id() !== intval($listing->user_id)) {
            abort(401, 'Unauthorized.');
        }
    }

    /**
     * Clears the preview cache
     *
     * @return void
     */
    protected function clearPreviewCache($id)
    {
        Cache::forget($this->cacheKey($id));
    }

    /**
     * Get the cache key.
     *
     * @param int $id
     * @return void
     */
    protected function cacheKey($id)
    {
        $userId = auth()->id();
        return "listing.preview.{$userId}.{$id}";
    }

    /**
     * Fills out a preview listing.
     *
     * @param integer $id
     * @return void
     */
    protected function fillPreviewListing($id)
    {
        $request = request();

        // Handle both create and update.
        if (intval($id) === 0) {
            $listing = new Listing;
            $listing->user_id = Auth::id();
        } else {
            $listing = Listing::findOrFail($id);
        }

        // Set preview photos
        $listing->previewPhotos = $this->addPreviewDocuments($listing, $listing->photos(), 'photos');

        // Set preview files.
        $listing->previewFiles = $this->addPreviewDocuments($listing, $listing->files(), 'files');

        // Fill the listing
        $listing->fill($this->getFields($request));

        // Fill the historical financials.
        $listing = $this->fillHistoricalFinancials($listing);

        // Set the listing to previewing
        $listing->previewing = true;

        return $listing;
    }

    /**
     * Adds preview documents.
     *
     * @param App\Listing $listing
     * @param \Illuminate\Support\Collection $documents
     * @param string $collection
     * @return \Illuminate\Support\Collection
     */
    protected function addPreviewDocuments($listing, $documents, $collection)
    {
        $request = request();
        $previewDocuments = $documents;

        // Remove deleted documents
        if (!empty($request->get($collection, ['deleted' => []])['deleted'])) {
            $previewDocuments = $previewDocuments
            ->whereNotIn('id', $request->get($collection)['deleted']);
        }

        // Add new documents
        foreach ($request->file("{$collection}.new", []) as $key => $newDocument) {
            $previewMedia = new PreviewMedia($newDocument);
            $previewMedia->id = "new-{$key}";
            $previewDocuments->push($previewMedia);
        }

        // Order Documents
        if (!empty($orderIds = $request->get($collection, ['order' => []])['order'] ?? [])) {
            $orderIds = array_flip($orderIds);
            $previewDocuments = $previewDocuments->sortBy(function ($document) use ($orderIds) {
                return intval($orderIds[$document->id] ?? 0);
            });
        }

        return $previewDocuments->values();
    }

    protected function fillHistoricalFinancials($listing)
    {
        if (empty(request()->only($this->yearRange()->flip()->toArray()))) {
            return $listing;
        }

        $yearlyData = new YearlyData($listing, false);

        // Update the year/quarter
        $listing->hf_most_recent_year = $yearlyData->createYear(request()->get('hf_most_recent_year'));
        $listing->hf_most_recent_quarter = request()->get('hf_most_recent_quarter');

        // Update the yearly data listing
        $yearlyData->setListing($listing);

        // Handle general data
        $listing->historicalFinancials = $yearlyData->saveGeneralData();

        // Handle revenues.
        $listing->revenues = $yearlyData->saveRevenueData();

        // Handle expenses.
        $listing->expenses = $yearlyData->saveExpenseData();

        return $listing;
    }
}
