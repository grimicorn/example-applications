@extends('layouts.marketing')

@section('content')
<div class="container">
    <h2 class="text-center fw-regular contact-page-title">You Have Questions? We Have Answers.</h2>

    <h3 class="text-center contact-page-subtitle">Have comments or questions? Use the form below to contact us.</h3>

    <div class="contact-content-wrap">
        @include('marketing.contact.form')
    </div>
</div>
@endsection
