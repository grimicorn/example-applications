@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @include('alerts.validation-errors')
                    @include('alerts.error')
                    @include('alerts.success')
                    @include('home.api-details', compact('user'))
                    @include('home.google-oauth2', compact('user', 'google_access_token_expired'))
                    @include('home/tasks-list-select', compact('list_options', 'user'))
                    @include('home/default-task-due-date', compact('user'))
                    @include('home.ifttt.example', compact('user'))
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
