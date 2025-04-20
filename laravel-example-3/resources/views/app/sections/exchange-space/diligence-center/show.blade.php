@extends('layouts.application')

@section('before-content')
    @include('app/sections/shared/exchange-space/message-action-bar')
@endsection

@section('content')
@include('app/sections/shared/exchange-space/messages', [
    'title' => $conversation->title,
    'subtitle' => $conversation->category_label,
])
@include('app/sections/shared/exchange-space/message-input')
@endsection
