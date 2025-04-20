<?php

namespace App\Http\Controllers\Application;

use App\Listing;
use App\Support\HasResponse;
use Illuminate\Http\Request;
use App\HistoricalFinancial;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Support\Listing\HasControllerHelpers;
use App\Support\HistoricalFinancial\YearlyData;
use App\Support\HistoricalFinancial\RevenueFormSections;
use App\Support\HistoricalFinancial\ExpenseFormSections;
use App\Support\HistoricalFinancial\GeneralDataFormSections;

class ListingHistoricalFinancialsController extends Controller
{
    use HasResponse;
    use HasControllerHelpers;

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $listing = Listing::with('historicalFinancials')->findOrFail($id);
        $historicalFinancial = $listing->listingCompletionScore->historicalFinancialCalculations;
        $generalData = new GeneralDataFormSections($listing);
        $sections = [
            'sources_of_income' => (new RevenueFormSections($listing))->section(),
            'non_recurring_personal_or_extra_expenses' => (new ExpenseFormSections($listing))->section(),
            'employee_related_expenses' => $generalData->employeeRelatedExpenses(),
            'office_related_expenses' => $generalData->officeRelatedExpenses(),
            'selling_general_and_administrative_expenses' => $generalData->sellingGeneralAndAdministrativeExpenses(),
            'finance_related_expenses' => $generalData->financeRelatedExpenses(),
            'other_cash_flow_items' => $generalData->otherCashFlowItems(),
            'balance_sheet_recurring_assets' => $generalData->balanceSheetRecurringAssets(),
            'balance_sheet_long_term_assets' => $generalData->balanceSheetLongTermAssets(),
            'balance_sheet_current_liabilities' => $generalData->balanceSheetCurrentLiabilities(),
            'balance_sheet_long_term_liabilities' => $generalData->balanceSheetLongTermLiabilities(),
            'balance_sheet_shareholders_equity' => $generalData->balanceSheetShareholdersEquity(),
        ];

        return view('app.sections.listing.historical-financials.edit', [
            'pageTitle' => 'Businesses',
            'pageSubtitle' => '',
            'section' => 'listings',
            'listing' => $listing,
            'yearRangeforSelect' => (new YearlyData($listing))->yearRangeForSelect(),
            'sections' => $sections,
            'mostRecentYear' => date('Y'),
            'section' => $section = 'listings',
            'type' => $type = 'historical-financials',
            'formId' => "application-{$section}-{$type}",
            'financialScore' => $historicalFinancial,
            'previewRoutes' => [
                route('listing.adjusted-financials.preview', ['id' => $listing->id]),
                route('listing.historical-financials.preview', ['id' => $listing->id]),
            ],
            'tourUrl' => '/tours/listing-historical-financials-edit',
            'tourEnabled' => false,
            'tourActivateLink' => route('listing.historical-financials.edit', ['id' => $listing->id, 'tour' => 1]),
            'previewStoreRoute' => route('listing.historical-financials.update', ['id' => $listing->id]),
            'previewStoreLabel' => 'Save & Preview',
            'previewSyncOnUpdate' => false,
        ]);
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
        $request->validate([
            'hf_most_recent_year' => 'required|date_format:Y',
        ]);

        $this->clearPreviewCache($id);

        $yearlyData = new YearlyData(
            $listing = Listing::findOrFail($id)
        );

        // Update the year/quarter
        $listing->hf_most_recent_year = $yearlyData->createYear($request->get('hf_most_recent_year'));
        $listing->hf_most_recent_quarter = $request->get('hf_most_recent_quarter');

        $listing->save();

        // Update the yearly data listing
        $yearlyData->setListing($listing);

        // Handle general data
        $yearlyData->saveGeneralData();

        // Handle revenues.
        $yearlyData->saveRevenueData();

        // Handle expenses.
        $yearlyData->saveExpenseData();

        // Store the listing completion score
        $listing->fresh()->listingCompletionScore->save();

        return $this->successResponse('Historical Financials updated successfully!', $request);
    }
}
