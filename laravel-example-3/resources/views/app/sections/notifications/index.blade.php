@extends('layouts.application')

@section('content')
    <app-notification-accordion
    :data-show-all="true"
    data-all-url="{{ route('notifications.index') }}"
    :data-notifications="{{ json_encode($notifications) }}"></app-notification-accordion>
@endsection
