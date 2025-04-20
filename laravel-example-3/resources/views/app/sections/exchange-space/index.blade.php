@extends('layouts.application')

@section('content')

<app-exchange-space-sort-table
:columns="{{ json_encode($columns) }}"
:paginated-items="{{ $paginatedSpaces->toJson() }}"
:route="{{ json_encode(route('exchange-spaces.index')) }}"
:listing-options="{{ json_encode($listingOptions) }}"></app-exchange-space-sort-table>

@endsection
