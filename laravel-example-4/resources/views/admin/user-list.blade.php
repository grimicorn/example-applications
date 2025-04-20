@extends('admin')

@section('title')
Users <strong>({{ $users->count() }})</strong>
@endsection

@section('panel')
@include('admin.users-list.table')
@endsection
