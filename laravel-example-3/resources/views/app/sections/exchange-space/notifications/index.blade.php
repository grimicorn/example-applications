@extends('layouts.application')

@section('content')
    <app-notification-accordion
            :data-show-all="true"
            data-all-url="{{ route('exchange-spaces.notifications.index', ['id' => $space->id]) }}"
            :data-notifications="{{ json_encode($notifications) }}"
    ></app-notification-accordion>
@endsection
