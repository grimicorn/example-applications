@extends('layouts.app', ['pageTitle' => "{$location->name}"])

@section('content')
<location-create-edit :data-is-update="true" v-cloak :data-location='{!! $location ? str_replace("'", '&apos;', $location->
    toJson()) : ' undefined'
    !!}'>
</location-create-edit>
@endsection
