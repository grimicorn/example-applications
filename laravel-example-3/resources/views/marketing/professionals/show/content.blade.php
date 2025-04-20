@php
    $criteria = $professional->desiredPurchaseCriteria;
    $profInfo = $professional->professionalInformation;
    $states = $profInfo->unabbreviated_areas_served_list;
@endphp

<div class="container">
    @include('marketing.professionals.show.header')

    @include('shared/partials/section-label-detail', [
        'title' => 'Personal Information',
        'hide' => !$professional->tagline && !$professional->bio,
        'wrappers' => [
            [
                'label' => 'Summary Tagline',
                'content' => $professional->tagline,
                'hide' => !$professional->tagline,
            ],
            [
                'label' => 'Bio',
                'content' => $professional->bio,
                'hide' => !$professional->bio
            ]
        ]
    ])

    @if(!empty($professional->primary_roles) and in_array('Buyer', $professional->primary_roles))
        @include('shared/partials/section-label-detail', [
            'title' => 'Acquisition Interests',
            'wrappers' => [
                [
                    'label' => 'Business Types',
                    'content' => comma_separated($criteria->businessCategoriesFiltered()->pluck('label')->filter()),
                    'hide' => $criteria->businessCategories()->isEmpty(),
                ],
                [
                    'label' => 'Location',
                    'content' => $criteria->locations,
                    'hide' => !$criteria->locations
                ],
                [
                    'label' => 'Asking Price',
                    'content' => price_range_format($criteria->asking_price_minimum, $criteria->asking_price_maximum),
                    'hide' => !$criteria->asking_price_minimum && !$criteria->asking_price_maximum
                ],
                [
                    'label' => 'Revenue',
                    'content' => price_range_format($criteria->revenue_minimum, $criteria->revenue_maximum),
                    'hide' => !$criteria->revenue_minimum && !$criteria->revenue_maximum
                ],
                [
                    'label' => 'EBITDA',
                    'content' => price_range_format($criteria->ebitda_minimum, $criteria->ebitda_maximum),
                    'hide' => !$criteria->ebitda_minimum && !$criteria->ebitda_maximum
                ],
                [
                    'label' => 'Pre-Tax Earning',
                    'content' => price_range_format($criteria->pre_tax_income_minimum, $criteria->pre_tax_income_maximum),
                    'hide' => !$criteria->pre_tax_income_minimum && !$criteria->pre_tax_income_maximum,
                ],
                [
                    'label' => 'Discretionary Cash Flow',
                    'content' => price_range_format($criteria->discretionary_cash_flow_minimum, $criteria->discretionary_cash_flow_maximum),
                    'hide' => !$criteria->discretionary_cash_flow_minimum && !$criteria->discretionary_cash_flow_maximum
                ]
            ]
        ])
    @endif

    @if(
    $profInfo->occupation_label ||
    $profInfo->years_of_experience ||
    $profInfo->company_name ||
    $profInfo->address ||
    $profInfo->links_with_protocol ||
    $profInfo->professional_background ||
    comma_separated($profInfo->license_qualifications) ||
    comma_separated($states))
        @include('shared/partials/section-label-detail', [
            'title' => 'Professional Information',
            'wrappers' => [
                [
                    'label' => 'Occupation',
                    'content' => $profInfo->occupation_label,
                    'hide' => !$profInfo->occupation_label,
                ],
                [
                    'label' => 'Years of Experience',
                    'content' => $profInfo->years_of_experience,
                    'hide' => !$profInfo->years_of_experience
                ],
                [
                    'label' => 'Company Name',
                    'content' => $profInfo->company_name,
                    'hide' => !$profInfo->company_name,
                ],
                [
                    'label' => 'Address',
                    'content' => $profInfo->address,
                    'hide' => !$profInfo->address,
                ],
                [
                    'label' => 'Links',
                    'content' => $profInfo->links_with_protocol,
                    'hide' => !$profInfo->links_with_protocol,
                    'isLink' => true
                ],
                [
                    'label' => 'Services Offered',
                    'content' => $profInfo->professional_background,
                    'hide' => !$profInfo->professional_background
                ],
                [
                    'label' => 'Professional Designations',
                    'content' => comma_separated($profInfo->license_qualifications),
                    'hide' => !$profInfo->license_qualifications,
                ],
                [
                  'label' => 'States Served',
                  'content' => comma_separated($states),
                  'hide' => !$states,
                ]
            ]
        ])
    @endif

    @if(!$myListings->isEmpty())
        <div class="row mb2 mb2-m">
            <div class="col-sm-12 flex items-center mb1 mb2-m">
                <h2 class="section-content-title flex-auto mb0">My Businesses</h2>
                <a
                        class="pull-right a-nd fc-color5"
                        href="{{ route('businesses.index', ['user' => $professional->id]) }}">
                    View All
                </a>
            </div>
            @foreach($myListings as $listing)
                <div class="col-sm-3 col-xs-6 my-listings mb2-m print-25">
                    <a class="a-nd img-link" href="{{ route('businesses.show', ['id'=> $listing->id]) }}">
                        <img src="{{$listing->cover_photo_roll_url }}"
                            alt="{{ $listing->title }} photo"
                            height="auto"
                            width="100%"
                            class="">
                    </a>

                    <a class="a-nd" href="{{ route('businesses.show', ['id'=> $listing->id]) }}">
                        <strong class="fc-color4 block lh-title">{{ stringTrim($listing->title, 26, strlen($listing->title) > 26) }}</strong>
                    </a>

                    @if(isset($listing->city) && isset($listing->state))
                    <div>
                        {{ implode(', ', [$listing->city, $listing->state]) }}
                    </div>
                    @endif

                    @isset($listing->asking_price_formatted)
                        <div class="fc-color5">${{number_format($listing->asking_price)}}</div>
                    @endisset
                </div>
            @endforeach
        </div>
    @endif
</div>
