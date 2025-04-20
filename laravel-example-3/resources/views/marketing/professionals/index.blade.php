@extends('layouts.marketing')

@section('content')
@php
// For some reason the old inputs are getting cleared.
// This transfers it and repopulates them.
if (!is_null(session()->get('contact_form_inputs'))) {
    session()->flashInput(session()->get('contact_form_inputs'));
}

@endphp
<div class="container">
    <div class="mt1-m">
        @include('marketing.professionals.index.search')
    </div>

    <div class="pb1 flex items-center bb1 mb3 flex-wrap">
        <h1 class="mb0 flex-auto">Search Results</h1>
        <marketing-search-sort-filter
        :total-results="{{ $professionals->total() }}"
        :per-page="{{ old('per_page', 25) }}"
        default-sort="{{ old('sort_order', 'title_a_to_z') }}"
        :sort-options="[
            {
                label: 'Name A-Z',
                value: 'title_a_to_z',
            },

            {
                label: 'Name Z-A',
                value: 'title_z_to_a',
            }
        ]"></marketing-search-sort-filter>
    </div>

    @if ($professionals->count() === 0)
    <alert :dismissible="false">
        No professionals found that match the selected search criteria.
    </alert>
    @endif

    @foreach($professionals as $professional)
        @php
            $profInfo = $professional->professionalInformation;
        @endphp
    <div class="row prof-result-card  pl2 pr2 pt2 pb3">

        <div class="col-xs-4 col-sm-3 prof-result-photo hide-mobile">
            <a href="{{ route('professional.show', ['id' => $professional->id]) }}">
                <avatar
                src="{{ $professional->photo_roll_url }}"
                width="250"
                height="250"
                initials="{{ $professional->initials }}"></avatar>
            </a>
        </div>

        <div class="col-sm-7">
            <div class="row">
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-3 prof-result-photo hide-desktop">
                                    <a href="{{ route('professional.show', ['id' => $professional->id]) }}">
                                        <avatar
                                        src="{{ $professional->photo_roll_url }}"
                                        width="250"
                                        height="250"
                                        initials="{{ $professional->initials }}"></avatar>
                                    </a>
                                </div>
                                <div class="col-xs-9 col-sm-12">
                                    <h2 class="mb1">
                                        <a
                                        class="a-nd a-color4"
                                        href="{{ route('professional.show', ['id' => $professional->id]) }}">
                                            {{$professional->name}}
                                        </a>
                                    </h2>
                                    <h3 class="mb1">{{$profInfo->occupation}}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <p class="mb2 pb2 bb1">{{$professional->tagline}}</p>
                        </div>

                        @if($profInfo->company_name)
                        <div class="col-xs-12 ">
                            <h2 class="mb1">{{$profInfo->company_name}}</h2>
                            <hr>
                        </div>
                        @endif

                        @if($profInfo->areas_served_list)
                        <div class="col-xs-12 flex">
                            <div>States Served:&nbsp;</div>
                            <div>{!! comma_separated($profInfo->unabbreviated_areas_served_list) !!}</div>
                        </div>
                        @endif

                        @if($profInfo->license_qualifications)
                        <div class="col-xs-12">
                            Professional Designations: {{ implode(', ', $profInfo->license_qualifications) }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-2">
            <div class="button-wrapper"><modal
            button-label="Contact"
            title="Email Me"
            button-class="btn-color4 mn-width-150 mb1"
            :auto-open="{{ (old('professional_id') == $professional->id) ? 'true' : 'false' }}"
            class="inline-block">
                @include('marketing.professionals.show.contact-form', ['inModal' => true])
            </modal>
            <a class="btn btn-color6 mn-width-150" href="{{ route('professional.show', ['id' => $professional->id]) }}">
                Details
            </a></div>
        </div>
    </div>
        <hr>
    @endforeach

    <div class="row">
        <div class="col-sm-6">
        <marketing-search-per-page-filter
            :total-results="{{ $professionals->total() }}"
            :per-page="{{ old('per_page', 25) }}"></marketing-search-per-page-filter>
        </div>
        <div class="col-sm-6">
            <model-pagination
                :paginated="{{ $professionals->toJson() }}"
                :allow-navigation="true"
                :align-left="true"></model-pagination>
        </div>
    </div>
</div>
@guest
    <div class="feature-switcher feature-switcher-large">
        <div class="feature-switcher-details-wrap  bg-color5 fc-color2">
            <div class="feature-switcher-content-image" style="background-image: url('/img/fe-professional-directory-cta.jpg');"></div>
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
