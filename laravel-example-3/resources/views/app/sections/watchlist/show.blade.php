@extends('layouts.application')

@section('before-content')
@component('app.sections.shared.action-bar')
@slot('left')
    <a
    href="{{ route('watchlists.index') }}"
    class="a-color7 fw-semibold">
        <i class="fa fa-chevron-left mr0" aria-hidden="true"></i>
        <span class="a-ul">Back to Watch Lists</span>
    </a>

@endslot
@endcomponent
@endsection


@section('content')
<div class="favorite-cards container">
    <app-listing-cards
    :data-listings="{{ $matches->toJson() }}"></app-listing-cards>

    <div class="row">
        <div class="col-sm-6">
            <marketing-search-per-page-filter
                :total-results="{{ $matches->total() }}"
                per-page="25"
            ></marketing-search-per-page-filter>
        </div>
        <div class="col-sm-6">
            <model-pagination
                :paginated="{{ $matches->toJson() }}"
                :allow-navigation="true"
                :align-left="true"
            ></model-pagination>
        </div>
    </div>
</div>

@endsection
