@extends('layouts.marketing')

@section('content')
<div class="container">
    <p>
        You are now leaving the Firm Exchange website to visit a third party site. Links provided are solely for your convenience and Firm Exchange is not responsible for the products, services, and content of the site. Please note that the third party's privacy policy and security practices may differ from those of Firm Exchange.
    </p>

    <div class="mb3 text-center">
        <a href="{{ $link }}" class="btn js-speed-bump-ignore">Continue</a>
    </div>
</div>
@endsection
