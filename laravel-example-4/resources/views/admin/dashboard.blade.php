@extends('admin')

@section('title')
Dashboard
@endsection

@section('panel')
<ul class="list-unstyled admin-dash-board-links">
    @include('admin.dashboard.links')
</ul>
@endsection
