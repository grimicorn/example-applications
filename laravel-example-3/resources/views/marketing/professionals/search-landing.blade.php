@extends('layouts.marketing') @section('content')
@php
// For some reason the old inputs are getting cleared.
// This transfers it and repopulates them.
if (!is_null(session()->get('contact_form_inputs'))) {
    session()->flashInput(session()->get('contact_form_inputs'));
}

@endphp
<div class="container">

	<div class="mt1">
    @include('marketing.professionals.index.search')
  </div>

	<div class="pb1 flex items-center bb1 mb3 flex-wrap">
      <h2 class="mb0 flex-auto mt3 h1">Search Business Professionals</h2>
	</div>
	<div class="mb3">
		Search our directory of business professionals to find a business broker, accountant, attorney or other professional near you.
	</div>
</div>
@guest
    <div class="feature-switcher feature-switcher-large">
        <div class="feature-switcher-details-wrap  bg-color5 fc-color2">
            <div class="feature-switcher-content-image" style="background-image: url('/img/fe-professional-directory-cta.jpg');" title="Find a professional to help with your business deal on Firm Exchange"></div>
            <div class="feature-switcher-content-wrap">
                <div class="inner">
                    <h3 class="feature-switcher-content-title fc-color2">Join the Professional Directory</h3>
                    <div class="feature-switcher-content">
                        Are you a business broker or other professional that would like to be included in the Professional Directory to advertise your services to the Firm Exchange Community? You can learn about the benefits of Firm Exchange by visiting our  <a href="/business-brokers" >Business Brokers</a> page or sign-up for a free account today.
                    </div>
                    <a href="{{ route('register') }}" class="btn btn-color2 btn-ghost">Sign Up</a>
                </div>
            </div>
        </div>
    </div>
@endguest
@endsection
