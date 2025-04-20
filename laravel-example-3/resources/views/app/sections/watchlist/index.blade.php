@extends('layouts.application')

@section('content')
<app-watchlist-form></app-watchlist-form>


<div>* denotes required fields</div>
@include('app.sections.watchlist.watchlist-table')
@endsection
