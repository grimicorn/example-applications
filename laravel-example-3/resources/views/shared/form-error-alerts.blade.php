{{-- General Error Alert --}}
@if(session('general-error'))
<div v-cloak>
    <alert type="error">{{ session('general-error') }}</alert>
</div>
@endif

{{-- General Errors Alert --}}
@if(session('general-errors'))
<div v-cloak>
    @foreach(session('general-errors') as $error)
    <alert type="error">{{ $error }}</alert>
    @endforeach
</div>
@endif

{{-- Error Alerts --}}
@if(count($errors) > 0)
<div v-cloak>
    <alert type="error">
        @isset($errorAlertValidationMessage)
        {{ $errorAlertValidationMessage }}
        @else
        Unable to save successfully. See below for errors.
        @endisset
    </alert>
</div>
@endif

{{-- Historical Financial Stale Data Error --}}
@if(isset($listing) and $listing->should_display_stale_data_alert)
<app-historical-financials-stale-data-alert
:data-stale="{{ $listing->listingCompletionScore->hasStaleData() ? 'true' : 'false' }}">
    {{ $listing->listingCompletionScore->staleDataErrorMessage(true) }}
</app-historical-financials-stale-data-alert>
@endisset
