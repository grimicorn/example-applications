@extends('layouts.app', ['pageTitle' => 'Add Location'])

{{-- @section('scripts')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ env('geocoder.key') }}&libraries=places">
</script>
@endsection --}}

@section('content')
<div class="mb-8">
    <a href="{{ route('location-from-google-map-link.create') }}" class="button">
        Quick Add from Google Map Link
    </a>
</div>
<location-create-edit :data-is-update="false" v-cloak :data-location='{!! $location ? str_replace("'", '&apos;', $location->toJson()) : ' undefined' !!}'>
</location-create-edit>
@endsection
