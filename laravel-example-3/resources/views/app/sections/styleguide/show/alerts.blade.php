@extends('layouts.styleguide')

@section('page-header')
@include('app.sections.shared.page-header', [
    'pageTitle' => 'Styleguide',
    'pageSubtitle' => 'Alerts',
])
@endsection

@section('styleguide-content')

<alert type="error">
    An error alert
</alert>

<alert type="success">
    A success alert
</alert>

<alert type="warning">
    A warning alert
</alert>

<alert type="info">
    An informational alert.
</alert>
@endsection
