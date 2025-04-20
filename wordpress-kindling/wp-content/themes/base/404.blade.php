@extends('layout.base')

@section('content')
    <div class="container text-center">
        <h1 class="mb-4">Error 404: Not Found</h1>

        <div class="alert alert-warning mb-8">
            Sorry, but the URL you entered does not exist. You can go <a href="{{home_url()}}">home</a> or try searching below.
        </div>
        <div class="max-w-sm mx-auto mb-16">
            {{ get_search_form() }}
        </div>
    </div>

@endsection
