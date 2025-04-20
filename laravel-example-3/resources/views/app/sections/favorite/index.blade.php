@extends('layouts.application')

@section('content')
    <app-favorite-cards
    :paginated-favorites="{{ json_encode($paginatedFavorites) }}"></app-favorite-cards>
@endsection
