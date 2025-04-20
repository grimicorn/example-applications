@extends('layouts.application')

@section('content')
<fe-form
method="POST"
submit-label="Search"
action="{{ route('lcs-custom-penalty.index') }}">
    <input-textual
    label="Listing ID"
    name="listing_id"
    type="text"
    validation="required"></input-textual>
</fe-form>
@endsection
