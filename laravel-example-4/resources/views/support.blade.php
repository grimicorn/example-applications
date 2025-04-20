@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Support</div>

                <div class="panel-body">
                    @if(session('form_success'))
                    <p class="text-center">
                        {{ session('form_success') }}
                    </p>
                    @else
                    @include('forms.support', ['user'])
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
