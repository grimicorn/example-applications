@extends('layouts.marketing')

@section('content')

    <div class="register-bar">
            <div class="container">
                <div class="row">
                    <div class="register-bar-first text-center col-sm-10 col-sm-offset-1">
                        <p class="fc-color4 cta">Firm Exchange offers a differentiated online experience to help business brokers find buyers and close more deals more quickly.</p>
                            <p class="">Our marketing team builds digital advertising campaigns specifically tailored for all posted listings. Each time you post a business for sale on Firm Exchange, you'll benefit from this complimentary service. This targeted approach drives more potential buyers to your clients' listings.
                            <br>
                            <br>
                            Secure your place on our innovative platform and get increased exposure for your listings. We offer unlimited listings for a fixed monthly price – no upsells or hidden fees.
                            </p>
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-color5 text-center">Get Started</a>
                        @endguest
                    </div> {{-- /.class --}}

                </div> {{-- /.row --}}
            </div> {{-- /.container --}}
        </div> {{-- /.register-bar --}}

{{-- Creation Section --}}
<div class="container content-section ">
    <div class="col-md-offset-1 col-md-10 smb-feature-list-full">
        <h2 class="text-center">Built from the ground up to keep buyers focused on what matters most, your listings</h2>

        <ul class="check-list fe-bullets-color5">
            <li>
                Professional, modern, and ad-free: our interface keeps buyers’ eyes on your listings
            </li>
            <li>
                Easily add your profile information to each listing, in turn driving more traffic to your portfolio and increasing your overall visibility
            </li>
            <li>
                Custom fields give you flexibility to add information that is unique to a specific business
            </li>
            <li>
                Our Business Inquiry tool with built-in communications platform lets you easily review and respond to interested buyers
            </li>
        </ul>
    </div>
</div>

{{--still need direction--}}
<div class="feature-half">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-1 col-md-7 content-left">
                <h3>Tools and resources to help you close more deals</h3>

                <p>Too often quality listings end up lost in the shuffle on other sites. At Firm Exchange, our <a href="/lc-rating">Listing Completion Rating</a> ensures the best listings get the most exposure, not who pays the most.</p>

                <p>A customizable dashboard helps you choose which businesses to prioritize. Stay in command of all your deals and receive notifications when there’s activity that warrants your attention.</p>

                <p>Each inquiry includes an option to start an Exchange Space with the potential buyer. This unique, workflow tool helps guide participants through the deal process and brings all the critical pieces of a deal together in one place.</p>
            </div>
            <div class="col-md-3">
              <div style="background: url('/img/fe_broker_handshake.jpg'); width: 100%; min-height: 350px; background-size: cover; background-position: top;" title="Firm Exchange helps business brokers focus on what's most important to them to close deals more efficiently"></div>
            </div> {{-- /.class --}}
        </div> {{-- /.row --}}
    </div> {{-- /.container --}}
</div> {{-- /.feature-half --}}

{{--still need direction--}}
<div class="feature-half">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-1 col-md-3">
              <div style="background: url('/img/fe-home-professional-directory.png'); width: 100%; min-height: 200px; background-size: cover; background-position: center;" title="Firm Exchange helps brokers gain visibility and connect with potential buyers and sellers"></div>
            </div> {{-- /.class --}}
            <div class="col-md-7 content-right">
                <h3>Showcase your expertise in the Professional Directory</h3>

                <p>Market your business brokerage expertise and gain extra exposure for you and your company by adding your profile to the Professional Directory. Gain new leads while networking with other members of the Firm Exchange community. No ads, no fees -- just another way we help get deals done more efficiently.</p>
            </div>
        </div> {{-- /.row --}}
    </div> {{-- /.container --}}
</div> {{-- /.feature-half --}}

{{-- Financial Analysis Tools --}}
@include('marketing.partials.feature-cards', [
    'cards' => [
        [
            'title' => 'Exchange Space',
            'content' => 'The deal process can be quite cumbersome, particularly when managing multiple deals at the same time. We make it easier with a fully integrated workflow solution, custom built for managing a small-business sale. Communication with each potential buyer is organized into its own deal space where you can manage deal members, keep track of progress with built-in deal stages, and share historical financials. Every Exchange Space also includes the Diligence Center, our custom designed solution to handle the needs of the due diligence process.',
            'iconClass' => 'fa fa-exchange',
        ],

        [
            'title' => 'Historical Financials Tool',
            'content' => 'Easily upload historical financial information that can be shared in an Exchange Space when you are ready. At that time, the tool provides a buyer with the business’ historical financials and a custom-built trend analysis to help with their due diligence. While not included in the publicly visible listing, historical financials are a key component of the LC Rating. As a broker, you understand buyers need this information, and our tool facilitates that discussion with your clients. More information leads to more visibility for their listing.',
            'iconClass' => 'fa fa-dollar',
        ],

        [
            'title' => 'Diligence Center',
            'content' => 'Our Diligence Center, custom built to handle the needs of the due diligence process, is the ideal solution to engage with buyers across multiple deals and get more deals closed, more quickly. All parties can engage in a dialogue that clusters messages into conversations, as specific or as broad as you need them, so you can work with business owners and efficiently answer buyer questions.',
            'iconClass' => 'fa fa-check-square',
        ],

        [
            'title' => 'Document Storage',
            'content' => 'Our software also allows users to attach documents to any Diligence Center message, letting you easily share documents to support buyer information requests. All documents are stored in one convenient location at the bottom of the Diligence Center home page, so you can easily review what has been shared and know your documents aren’t lost in someone’s inbox.',
            'iconClass' => 'fa fa-paperclip',
        ],
    ],
    'colClass' => 'col-sm-6',
    'title' => 'Firm Exchange is a better way to manage your deal flow',
    'content' => 'At Firm Exchange, we know that when it comes to selling businesses, proper preparation is key to positioning the business for a successful, efficient transaction. That’s why we built industry-leading tools to help you work with business owners to prepare for and manage the deal process.',
])


<div class="container smb-plans-section mt3 mb3">
<h2 class="fc-color4 text-center">Join for free and start creating your listings today.<br><br>You only pay when you post your listings and make them available for search.</h2>

<p class="text-center">
For a limited time, we are offering introductory pricing when you post your listings.
</p>
<p class="text-center">
We want our business broker customers focused on finding buyers and closing deals, not worrying about listing limits or additional costs. <br>That’s why we offer straightforward pricing without any up-sells or add-ons.
</p>
@include('marketing.partials.pricing')
<div class="text-center"><h4 class="fc-color5">Learn more about our pricing by visiting our <a href="/pricing">Pricing</a> page</h4></div>
</div> {{-- /.smb-plans-section --}}
@endsection
