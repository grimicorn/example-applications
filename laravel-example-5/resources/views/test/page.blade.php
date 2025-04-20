@extends('layouts.marketing')

@section('content')
    <div class="container m-auto">
        URL: {{ $url }}
        {{ uniqid() }}

        <window-dimensions></window-dimensions>

        <button style="width: 100%;height:200px;padding:20px;">HMMMM IM A BUTTON</button>
    </div>
@endsection
