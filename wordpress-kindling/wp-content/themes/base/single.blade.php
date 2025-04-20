@extends('layout.base')

@section('content')
    @include('layout.page-header')
    <div class="container">
        <div class="flex mb-4">

            <div class="mr-4">{{get_the_date()}}</div>

            {{the_category(',')}}

        </div>
        @include('content.loop')

        <a href="{{ get_post_type_archive_link( 'post' ) }}">
            <i class="fas fa-arrow-left"></i>
            &nbsp;Back to Blog Home
        </a>
    </div>
@endsection
