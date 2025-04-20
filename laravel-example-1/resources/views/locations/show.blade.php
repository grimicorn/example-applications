@extends('layouts.app', ['pageTitle' => "{$location->name}"])

@section('content')
<locations-index-list-item :data-location='{{ $location->toJson() }}' :data-force-moreinfor-open="true"
    :data-display-location-images="true" :data-display-view-show="false">
</locations-index-list-item>
@endsection
