@extends('layouts.application')

@section('content')
<h3 class="h1 fc-color5 flex items-center justify-center">
    <span class="mr1">Your Current LC Rating</span>
    <lc-rating-tooltip class="fz-20"></lc-rating-tooltip>
</h3>


        <div class="container">
            <div class="row">
                <div class="col-md-offset-1 col-md-10 smb-feature-list-full">
                    <p>The Listing Completion Rating (LC Rating) assigns a star-based rating to a listing based on how much information is provided relative to other listings on the platform, posted or otherwise.</p>
                </div>
                <div class="col-md-offset-1 col-md-6 content-left">
                    <p>Two things are important to note: one, as you can see from the chart, fewer stars does not mean a listing is bad, simply that it currently has less information. Secondly, you can post a listing at any time and update it with more information as you get it -- don’t feel like you have to have all the answers at the outset!</p>
                </div>
                <div class="col-md-4">
                    <img src="/img/LC-Rating-Legend.png" title="Firm Exchange's LC Rating system assigns a star rating based on the information provided in a listing">
                </div> {{-- /.class --}}
            </div> {{-- /.row --}}
        </div> {{-- /.container --}}


<lc-rating
class="fz-64 fc-color5 justify-center mb45 mt3"
:data-percentage-complete="{{ floatval($score->totalPercentage()) }}"></lc-rating>

{{-- Required Information (Note: This was previously "The Basics")--}}
<app-form-accordion
header-title="Business Details"
class="listing-completion-score-the-basics">
    <div slot="header-right">
        <app-listing-section-completion-bar
        :percentage-complete="{{ $businessOverview->totalPercentage() }}"></app-listing-section-completion-bar>
    </div>
    <div slot="content">
        @include('app.sections.listing.completion-score.header')

        @include('app.sections.listing.completion-score.row', [
            'label' => 'Required Information',
            'percentage' => $businessOverview->sectionPercentageForDisplay('basics'),
            'link' => route('listing.details.edit', ['id' => $listing->id, '#thebasics']),
        ])

        @include('app.sections.listing.completion-score.row', [
            'label' => 'More About the Business',
            'percentage' => $businessOverview->sectionPercentageForDisplay('more_about_the_business'),
            'link' => route('listing.details.edit', ['id' => $listing->id, '#moreAboutTheBusiness']),
        ])

        @include('app.sections.listing.completion-score.row', [
            'label' => 'Financial Details',
            'percentage' => $businessOverview->sectionPercentageForDisplay('financial_details'),
            'link' => route('listing.details.edit', ['id' => $listing->id, '#financialDetails']),
        ])

        @include('app.sections.listing.completion-score.row', [
            'label' => 'Business Details',
            'percentage' => $businessOverview->sectionPercentageForDisplay('business_details'),
            'link' => route('listing.details.edit', ['id' => $listing->id, '#businessDetails']),
        ])

        @include('app.sections.listing.completion-score.row', [
            'label' => 'Transaction Considerations',
            'percentage' => $businessOverview->sectionPercentageForDisplay('transaction_considerations'),
            'link' => route('listing.details.edit', ['id' => $listing->id, '#transactionConsiderations']),
        ])

        @include('app.sections.listing.completion-score.row', [
            'label' => 'Upload Documents',
            'percentage' => $businessOverview->sectionPercentageForDisplay('uploads'),
            'link' => route('listing.details.edit', ['id' => $listing->id, '#uploadDocuments']),
            'last' => true,
        ])
    </div>
</app-form-accordion>

{{-- Historical Financials --}}
<app-form-accordion
header-title="Historical Financials"
class="listing-completion-score-historical-financials">
    <div slot="header-right">
        <app-listing-section-completion-bar
        :percentage-complete="{{ $historicalFinancial->totalPercentage() }}"></app-listing-section-completion-bar>
    </div>
    <div slot="content">
        @include('app.sections.listing.completion-score.header')

        @include('app.sections.listing.completion-score.row', [
            'label' => 'Sources of Income',
            'percentage' => $historicalFinancial->sectionPercentageForDisplay('sources_of_income'),
            'link' => route('listing.historical-financials.edit', [
                'id' => $listing->id,
                '#sources_of_income',
            ]),
        ])
        @include('app.sections.listing.completion-score.row', [
            'label' => 'Employee Related Expenses',
            'percentage' => $historicalFinancial->sectionPercentageForDisplay('employee_related_expenses'),
            'link' => route('listing.historical-financials.edit', [
                'id' => $listing->id,
                '#employee_related_expenses',
            ]),
        ])
        @include('app.sections.listing.completion-score.row', [
            'label' => 'Office Related Expenses',
            'percentage' => $historicalFinancial->sectionPercentageForDisplay('office_related_expenses'),
            'link' => route('listing.historical-financials.edit', [
                'id' => $listing->id,
                '#office_related_expenses',
            ]),
        ])
        @include('app.sections.listing.completion-score.row', [
            'label' => 'Selling, General, and Administrative Expenses',
            'percentage' => $historicalFinancial->sectionPercentageForDisplay('selling_general_and_administrative_expenses'),
            'link' => route('listing.historical-financials.edit', [
                'id' => $listing->id,
                '#selling_general_and_administrative_expenses',
            ]),
        ])
        @include('app.sections.listing.completion-score.row', [
            'label' => 'Finance Related Expenses',
            'percentage' => $historicalFinancial->sectionPercentageForDisplay('finance_related_expenses'),
            'link' => route('listing.historical-financials.edit', [
                'id' => $listing->id,
                '#finance_related_expenses',
            ]),
        ])

        @include('app.sections.listing.completion-score.row', [
            'label' => 'Other Cash Flow Items',
            'percentage' => $historicalFinancial->sectionPercentageForDisplay('other_cash_flow_items'),
            'link' => route('listing.historical-financials.edit', [
                'id' => $listing->id,
                '#other_cash_flow_items',
            ]),
        ])

        @include('app.sections.listing.completion-score.row', [
            'label' => 'Non-recurring, Personal, or Extra Expenses',
            'percentage' => $historicalFinancial->sectionPercentageForDisplay('non_recurring_personal_or_extra_expenses'),
            'link' => route('listing.historical-financials.edit', [
                'id' => $listing->id,
                '#non_recurring_personal_or_extra_expenses',
            ]),
        ])
        @include('app.sections.listing.completion-score.row', [
            'label' => 'Balance Sheet – Recurring Assets',
            'percentage' => $historicalFinancial->sectionPercentageForDisplay('balance_sheet_recurring_assets'),
            'link' => route('listing.historical-financials.edit', [
                'id' => $listing->id,
                '#balance_sheet_recurring_assets',
            ]),
        ])
        @include('app.sections.listing.completion-score.row', [
            'label' => 'Balance Sheet – Long-term Assets',
            'percentage' => $historicalFinancial->sectionPercentageForDisplay('balance_sheet_long_term_assets'),
            'link' => route('listing.historical-financials.edit', [
                'id' => $listing->id,
                '#balance_sheet_long_term_assets',
            ]),
        ])
        @include('app.sections.listing.completion-score.row', [
            'label' => 'Balance Sheet – Current Liabilities',
            'percentage' => $historicalFinancial->sectionPercentageForDisplay('balance_sheet_current_liabilities'),
            'link' => route('listing.historical-financials.edit', [
                'id' => $listing->id,
                '#balance_sheet_current_liabilities',
            ]),
        ])
        @include('app.sections.listing.completion-score.row', [
            'label' => 'Balance Sheet – Long-term Liabilities',
            'percentage' => $historicalFinancial->sectionPercentageForDisplay('balance_sheet_long_term_liabilities'),
            'link' => route('listing.historical-financials.edit', [
                'id' => $listing->id,
                '#balance_sheet_long_term_liabilities',
            ]),
        ])
        @include('app.sections.listing.completion-score.row', [
            'label' => 'Balance Sheet – Shareholder’s Equity',
            'percentage' => $historicalFinancial->sectionPercentageForDisplay('balance_sheet_shareholders_equity'),
            'link' => route('listing.historical-financials.edit', [
                'id' => $listing->id,
                '#balance_sheet_shareholders_equity',
            ]),
            'last' => true,
        ])
    </div>
</app-form-accordion>
@endsection
