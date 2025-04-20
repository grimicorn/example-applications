@extends('layouts.app', ['pageTitle' => 'Locations'])

@section('scripts')
<script>
    window.Domain.centerCoordinates = JSON.parse('{!! addslashes($coordinates->toJson()) !!}');
    window.Domain.searchLocations = JSON.parse('{!! addslashes($searchLocations) !!}');
</script>
@endsection

@section('content')
<locations-index></locations-index>
@endsection
