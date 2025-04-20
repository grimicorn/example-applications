@extends('layouts.app')

@section('content')
    <jobs-index
        class="container mx-auto"
        :data-paginated-jobs="{{ $jobs->toJson() }}"
    >
    </jobs-index>
@endsection
