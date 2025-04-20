@extends('layouts.marketing')

@section('content')
{{-- Featured Image --}}
<div class="home-featured-image flex" title="Small business owner discussing a product with a customer.">
    <div class="container inner">

        <p class="line-1">
            Firm Exchange is reimagining how people buy and sell small businesses.<br>
            <br>
            More than just a listing site &#8212; we bring all the parties together and provide the tools and resources to get deals done.
        </p>
        @guest
            <p class="line-2">
                Join today to gain access to fantastic features and benefits that can help make your deal a success.
            </p>

            <a href="{{ route('register') }}" class="btn btn-color5 mb3">Get Started</a>
        @endguest
    </div>
</div>

{{-- Content Area --}}
<div class="home-content-area container">
    <h1 class="text-center home-content-area-title">Firm Exchange’s innovative platform is the modern approach to
    <br>
    buying and selling small businesses.</h1>

    <p class="fc-color7 fw-semibold text-center">
    At Firm Exchange, we understand that buying or selling a small business is an emotional process, and it is important to get it right. That’s why we built a marketplace and deal management platform with the tools and resources to help guide you every step of the way. Whether you are buying or selling a business for the first time or the tenth, do it confidently with Firm Exchange.<br><br>Looking to learn more about the deal process? Visit our <a href="https://blog.firmexchange.com">blog</a> for helpful articles to get you started.
    </p>

    <div class="col-md-offset-1 col-md-5 center-list smb-feature-list-full">
        <h2 class="text-center">We help buyers:</h2>
        <ul class="check-list fe-bullets-color5 single-column">
            <li>
                Conduct and save searches with easy-to-use filters and search parameters
            </li>
            <li>
                Provide a platform to bring the entire deal team together for the due diligence process
            </li>

            <li>
                Achieve their dreams of being their own boss
            </li>
        </ul>
    </div>
    <div class="col-md-5 center-list smb-feature-list-full">
        <h2 class="text-center">We help sellers:</h2>
        <ul class="check-list fe-bullets-color5 single-column">
            <li>
                Create professional, ad-free listings and financial statements to share with buyers
            </li>

            <li>
                Manage the deal process with user-friendly tools and step-by-step guides to help make their deal a success
            </li>

            <li>
                Realize the value of the hard work they have invested
            </li>
        </ul>
    </div>
</div>

{{-- Feature Switcher --}}
<div class="home-feature-switcher">
    <marketing-feature-switcher
    :features="{{ json_encode($home['featureSwitcher']) }}"></marketing-feature-switcher>
</div>


{{-- User Category Buckets --}}
@include('marketing.partials.feature-cards', [
    'cards' => $home['userFeatureCards'],
    'title' => 'Here are just a few of the features you’ll get when you join Firm Exchange:',
    'hasBackground' => false,
])

@endsection
