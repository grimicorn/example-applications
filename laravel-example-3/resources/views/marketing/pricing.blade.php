@extends('layouts.marketing')

@section('content')

{{-- Creation Section --}}
<div class="container">
    <div class="col-md-offset-1 col-md-10 smb-feature-list-full">
        <h2 class="text-center">Two simple pricing options to fit your needs – with no upsells or hidden fees</h2>

         <p class="text-center">
            To celebrate our launch, we are offering <strong class="fc-color4">50% off</strong> our planned future pricing.
        </p>
    </div>
</div>


<div class="container smb-plans-section">
    @include('marketing.partials.pricing')
</div>

<div class="container">
    <div class="col-md-offset-1 col-md-10 smb-feature-list-full">
        <h2 class="fc-color6 text-center">We don’t charge for listing placement, bigger photos, or colored borders. And best of all – you only pay when you’re ready to post your listing.</h2>
    </div>
</div>

<div class="featured-cards-bg mb3" style="background-position:top;">

    <div class="featured-cards container">
        <h2 class="featured-cards-title text-center section-title">Firm Exchange is the Hub Where Successful Deals Happen</h2>
        <p class="text-center featured-cards-content">
            We provide tremendous value for serious buyers and sellers. Firm Exchange offers an ad-free experience unlike other listing sites while pulling together the full range of tools and resources you need to complete your deal.
        </p>
    </div>
</div> {{-- /.featured-cards-bg --}}


<div class="feature-grid">
    <div class="container">
        <div class="row">
        	<div class="col-md-10 col-md-offset-1 pt4">
                <div class="col-sm-4 mb3">
                    <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
                    <p>Guidence &amp; Education</p>
                </div> {{-- /.class --}}
                <div class="col-sm-4 mb3">
                    <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                    <p>Listing Services</p>
                </div> {{-- /.class --}}
                <div class="col-sm-4 mb3">
                    <i class="fa fa-comments-o" aria-hidden="true"></i>
                    <p>Diligence Tracker</p>
                </div> {{-- /.class --}}
                <div class="col-sm-4 mb3">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <p>Professional Directory</p>
                </div> {{-- /.class --}}
                <div class="col-sm-4 mb3">
                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                    <p>Document Storage</p>
                </div> {{-- /.class --}}
                <div class="col-sm-4 mb3">
                    <i class="fa fa-dollar" aria-hidden="true"></i>
                    <p>Financial Tools</p>
                </div> {{-- /.class --}}
            </div>
        </div> {{-- /.row --}}
    </div> {{-- /.container --}}
</div> {{-- /.feature-grid --}}
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 pt4">
            <h2 class="fc-color4 text-center">By having all of <span class="fc-color5"><a href="/how-it-works">these features</a></span> in one place at Firm Exchange, you can save hundreds of dollars versus cobbling them together from other providers</h2>
        </div>
    </div>
</div>

<div class="container smb-plans-section">



</div> {{-- /.smb-plans-section --}}
@endsection
