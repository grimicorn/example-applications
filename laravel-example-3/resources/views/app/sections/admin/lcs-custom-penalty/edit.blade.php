@extends('layouts.application')

@section('before-content')
    @component('app.sections.shared.action-bar')
        @slot('left')
        <a href="{{ route('lcs-custom-penalty.index') }}">&lt; Back to Search</a>
        @endslot
    @endcomponent
@endsection

@section('content')
@if($listing)
<fe-form
method="POST"
action="{{ route('lcs-custom-penalty.update', ['id' => $id]) }}">
    <input-textual
    label="Penalty Percentage (0-100)"
    name="custom_penalty"
    type="number"
    input-min="0"
    input-step="1"
    input-max="100"
    placeholder="0-100"
    validation="required|min_value:0|max_value:100"
    value="{{ $listing->listingCompletionScoreTotal->custom_penalty }}"></input-textual>
</fe-form>
@else
Business for id {{ $id }} Not found
@endif
@endsection
