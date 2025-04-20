@extends('layouts.application')

@section('page-header')
@include('app.sections.shared.page-header', [
    'pageTitle' => 'Styleguide',
])
@endsection

@section('content')
<ul class="stylequide-links">
    <li>
        <a href="{{ route('styleguide.show.general', ['slug' => 'general']) }}">General</a>
    </li>

    <li>
        <a href="{{ route('styleguide.show.alerts', ['slug' => 'alerts']) }}">Alerts</a>
    </li>

    <li>
        <a href="{{ route('styleguide.show.inputs', ['slug' => 'inputs']) }}">Inputs</a>
    </li>
</ul>
@endsection
