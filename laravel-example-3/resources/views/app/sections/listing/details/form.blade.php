@php
$section = 'listings';
$type = 'details';
$formId = "application-{$section}-{$type}";
$overviewScore = $listing->listingCompletionScore->businessOverviewCalculations;
$listingJson = $listing->makeVisible([
    'address_visible',
    'business_description',
    'products_services',
    'seller_non_compete',
    'address_1',
    'address_2',
    'city',
    'state',
    'zip_code',
])->toJson();
@endphp

@extends('layouts.application')

@include('app.sections.listing.partials.form-actions', [
    'section' => $section,
    'type' => $type,
])

@section('content')
@component('app.sections.shared.form', [
    'section' => $section,
    'type' => $type,
    'action' => $action,
    'formTitle' => $formTitle,
    'hasFormHeader' => true,
    'method' => $method,
    'enableRedirect' => isset($enableRedirect) ? $enableRedirect : false,
])

    <div class="pull-right pb1">* denotes required fields</div>

    {{-- Required Information (Note: This was previously "The Basics") --}}
        {{-- The Basics --}}
        <app-listing-basics-accordion
        id="theBasics"
        :data-listing="{{ $listingJson }}"
        form-id="{{ $formId }}"
        :listing-types="{{ json_encode($listingTypes) }}"
        :data-business-parent-categories="{{ json_encode($businessParentCategories) }}"
        :data-business-child-categories="{{ json_encode($businessChildCategories) }}"
        :percentage-complete="{{ $overviewScore->sectionPercentage('basics') }}"></app-listing-basics-accordion>

        {{-- More About The Business --}}
        <app-listing-more-about-the-business-accordion id="moreAboutTheBusiness"
        :data-listing="{{ $listingJson }}"
        form-id="{{ $formId }}"
        :percentage-complete="{{ $overviewScore->sectionPercentage('more_about_the_business') }}"></app-listing-more-about-the-business-accordion>

        {{-- Financial Details --}}
        <app-listing-financial-details-accordion id="financialDetails"
        :data-listing="{{ $listingJson }}"
        form-id="{{ $formId }}"
        :financial-detail-options="{{ json_encode($financialDetailOptions) }}"
        :percentage-complete="{{ $overviewScore->sectionPercentage('financial_details') }}"></app-listing-financial-details-accordion>

        {{-- Details of the Business --}}
        <app-listing-details-of-the-business-accordion id="businessDetails"
        :data-listing="{{ $listingJson }}"
        form-id="{{ $formId }}"
        :percentage-complete="{{ $overviewScore->sectionPercentage('business_details') }}"></app-listing-details-of-the-business-accordion>

        {{-- Upload Documents --}}
        <app-listing-upload-documents-accordion id="uploadDocuments"
        :data-listing="{{ $listingJson }}"
        form-id="{{ $formId }}"
        :photos="{{ $listing->photosForDisplay()->toJson() }}"
        :files="{{ $listing->filesForDisplay()->toJson() }}"
        :percentage-complete="{{ $overviewScore->sectionPercentage('uploads') }}"></app-listing-upload-documents-accordion>
    @endcomponent

    @if($listing->id)
        <app-listing-exit-survey-modal
        :data-exit-survey="{{ json_encode($listing->exitSurvey) }}"
        class="mt1"
        :data-disable-space-close="true"
        data-submit-route="{{ route('listing.destroy', ['id' => $listing->id]) }}"></app-listing-exit-survey-modal>
    @endif
@endsection

@section('sidebar')
    <app-sticky-form-buttons>
        <app-teleport-click-button
        class="width-100 mb2 btn-color6"
        data-label="Save"
        data-to-selector=".btn-model-submit"></app-teleport-click-button>

        <app-teleport-click-button
        class="width-100 mb2 btn-color5"
        data-label="Preview"
        data-to-selector=".model-preview-button"></app-teleport-click-button>
    </app-sticky-form-buttons>
@endsection
