@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if(route('adminDashboard') == Request::url())
                    Administrator/@yield('title')
                    @else
                    <a href="/admin/">Administrator</a>/@yield('title')
                    @endif
                </div>

                <div class="panel-body">
                    @yield('panel')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
