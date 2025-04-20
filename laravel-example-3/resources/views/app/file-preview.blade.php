@extends('layouts.preview')

@section('content')
    @if($file_url)
        @if($is_image)
            <div class="flex bg-color1 items-center justify-center h-100 w-100">
            <img src="{{ $file_url }}" alt="">
            </div>
        @else
            <iframe src="{{ $file_url }}" frameborder="0" style="width:100%;height:100%"></iframe>
        @endif
    @endif
@endsection
