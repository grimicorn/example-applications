<div class="container">
		<div class="row">
			<div class="col-md-9">
				@if(!isset($isAdmin) or !$isAdmin)
					<a
					class="fc-color5 fw-bold text-uppercase mt1 block pb2 hide-print"
					href="{{ route('businesses.index') }}">Back to search results</a>
				@endif

				<h1 class="fc-color4 text-capitalize mr1">
					{{$business->title}}
					@if(isset($business->city) && isset($business->state))
						<div class="subhead width-49 pt1 fc-color6 text-capitalize">
							{{ $business->city }}, {{$business->state}}
						</div>
					@endif
				</h1>
			</div>

			<div class="col-md-3 hide-print">
				<div class="row">
					@if(!isset($isAdmin) or !$isAdmin)
						<div class="col-xs-6 col-md-12 pt1 contact-seller-button">
							<app-create-inquiry
                            :listing="{{ $business->toJson() }}"></app-create-inquiry>
						</div>
					@endif

					<div class="col-xs-6 col-md-12 text-right pt1 pb2 action-bar hide-print">
						<print-page class="pr1"></print-page>

						@include('shared.partials.share-page')

					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<div class="bb1 mb3"></div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 print-pull-left">
				<div class="featured-image">
                    @isset($slides)
					<slider
                    :data-slides="{{ $slides->toJson() }}"></slider>
                    @endisset
				</div>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-6 print-pull-right">
				<div class="row">
					<div class="col-xs-12 col-sm-6 print-50">
						<div class="price-highlight price-grid">
							<div class="text-bold fc-color4 fz-14">Asking Price:</div>
							<div class="fz-26 text-bold fc-color5">
								{{isset($business->asking_price) ? "$".number_format($business->asking_price) : "-"}}</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 print-50">
						<div class="price-grid">
							<div class="text-bold fc-color4 fz-14">Cash Flow:</div>
							<div class="fz-26 text-bold fc-color5">
								{{isset($business->discretionary_cash_flow) ? "$".number_format($business->discretionary_cash_flow) : "-"}}</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-6 print-50">
						<div class="price-grid">
							<div class="text-bold fc-color4 fz-14">Revenue:</div>
							<div class="fz-26 text-bold fc-color5">
								{{isset($business->revenue) ? "$".number_format($business->revenue) : "-"}}</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 print-50">
						<div class="price-grid">
							<div class="text-bold fc-color4 fz-14">Inventory:</div>
							<div class="fz-26 text-bold fc-color5">
								{{ listing_na_included_price(
									$business->inventory_included,
									$business->inventory_estimated
								) }}
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-6 print-50">
						<div class="price-grid">
							<div class="text-bold fc-color4 fz-14">EBITDA:</div>
							<div class="fz-26 text-bold fc-color5">
								{{isset($business->ebitda) ? "$".number_format($business->ebitda) : "-"}}</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 print-50">
						<div class="price-grid">
							<div class="text-bold fc-color4 fz-14">Fixtures & Equipment:</div>
							<div class="fz-26 text-bold fc-color5">
								{{ listing_na_included_price(
								$business->fixtures_equipment_included,
								$business->fixtures_equipment_estimated
								) }}
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-6 print-50">
						<div class="price-grid">
							<div class="text-bold fc-color4 fz-14">Pre-Tax Income:</div>
							<div class="fz-26 text-bold fc-color5">
								{{isset($business->pre_tax_earnings) ? "$".number_format($business->pre_tax_earnings) : "-"}}</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 print-50">
						<div class="price-grid">
							<div class="text-bold fc-color4 fz-14">Real Estate:</div>
							<div class="fz-26 text-bold fc-color5">
								{{ listing_na_included_price(
									$business->real_estate_included,
									$business->real_estate_estimated
								) }}
							</div>
						</div>
					</div>
				</div>

				@if($business->real_estate_included == \App\Support\AssetIncludedOptionType::NOT_INCLUDED
				|| $business->fixtures_equipment_included == \App\Support\AssetIncludedOptionType::NOT_INCLUDED
				|| $business->inventory_included == \App\Support\AssetIncludedOptionType::NOT_INCLUDED)
				<div class="row">
					<div class="col-sm-offset-6 col-sm-6 pt1">
						<span class="fz-26 vam text-bold fc-color5">&#42;</span> Not Included in the Asking Price.
					</div>
				</div>
				@endif
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<div class="section section-business-description mt3-m">
					@if(!empty($business->summary_business_description)) <h2 class="section-content-title">Summary Tagline</h2> @endif
					<div class="mb3 mb3-m lh-copy">
						{{$business->summary_business_description}}
					</div>
					@if(!empty($business->business_description)) <h2 class="section-content-title">Business Overview</h2> @endif
					<div class="mb2 mb2-m lh-copy">
						{{$business->business_description}}
					</div>
				</div>
				<div class="section section-business-details">
					@if($business->name_visible)
					<div>@include('shared/partials/section-label-detail',
						[
							'title' => 'Business Details',
							'wrappers' => [
								[
									'label' => 'Business Name',
									'content' => $business->business_name
								]
							],
							'disable_break' => true
						])
					</div>
					@endif

					<div>
						@include('shared/partials/section-label-detail',
							[
								'title' => $business->name_visible ? '' : 'Business Details',
								'wrappers' => [
									[
										'label' => 'Year Established',
										'content' => $business->year_established,
										'hide' => !$business->year_established
									],
									[
										'label' => 'Number of Employees',
										'content' => $business->number_of_employees,
										'hide' => !$business->number_of_employees
									],
									[
										'label' => 'Location',
										'content' => $business->address,
										'hide' => !$business->address
									],
									[
										'label' => 'Location Description',
										'content' => $business->location_description,
										'hide' => !$business->location_description
									],
									[
										'label' => 'Real Estate Description',
										'content' => listing_na_included_description($business->real_estate_included, $business->real_estate_description),
										'hide' => !listing_na_included_description($business->real_estate_included, $business->real_estate_description),
									],
									[
										'label' => 'Fixtures & Equipment Description',
										'content' => listing_na_included_description($business->fixtures_equipment_included, $business->fixtures_equipment_description),
										'hide' => !listing_na_included_description($business->fixtures_equipment_included, $business->fixtures_equipment_description),
									],
									[
										'label' => 'Inventory Description',
										'content' => listing_na_included_description($business->inventory_included, $business->inventory_description),
										'hide' => !listing_na_included_description($business->inventory_included, $business->inventory_description),
									],
									[
										'label' => 'Links',
										'content' => $business->links_with_protocol,
										'isLink' => true,
										'hide' => !$business->links_with_protocol
									],
									[
										'label' => 'Products & Services',
										'content' => $business->products_services,
										'hide' => !$business->products_services
									],
									[
										'label' => 'Market Overview',
										'content' => $business->market_overview,
										'hide' => !$business->market_overview
									],
									[
										'label' => 'Competitive Position',
										'content' => $business->competitive_position,
										'hide' => !$business->competitive_position
									],

									[
										'label' => 'Performance & Outlook',
										'content' => $business->business_performance_outlook,
										'hide' => !$business->business_performance_outlook
									],

									[
										'label' => $business->custom_label_1,
										'content' => $business->custom_input_1,
										'hide' => !$business->custom_label_1 || !$business->custom_input_1
									],

									[
										'label' => $business->custom_label_2,
										'content' => $business->custom_input_2,
										'hide' => !$business->custom_label_2 || !$business->custom_input_2
									],

									[
										'label' => $business->custom_label_3,
										'content' => $business->custom_input_3,
										'hide' => !$business->custom_label_3 || !$business->custom_input_3
									],

									[
										'label' => $business->custom_label_4,
										'content' => $business->custom_input_4,
										'hide' => !$business->custom_label_4 || !$business->custom_input_4
									],

									[
										'label' => $business->custom_label_5,
										'content' => $business->custom_input_5,
										'hide' => !$business->custom_label_5 || !$business->custom_input_5
									],
								]
							]
						)
					</div>
				</div>

				@if($business->display_listed_by)
					<div class="section section-listed-by pb4">
						<div class="text-uppercase pb1 text-bold">Sold By:</div>
						@include('shared/partials/listing-user-card')
					</div>
				@endif

				@if(!$business->files()->isEmpty())
				<div class="section section-attached-files">
					<div class="pt1 pb1">
						<h2 class="section-content-title inline-block pr1">Attached Files</h2>
						@foreach($business->files() as $file)
							<div class="attached-file pb1">
								{{--{{dd($file->mime_type)}}--}}
								<app-file-preview-link
								url="{{$file->getFullUrl()}}"
								mime-type="{{$file->mime_type}}"
								label="{{$file->name}}"
								></app-file-preview-link>
								{{--<img--}}
									{{--src="{{ $file->getFullUrl() }}"--}}
									{{--alt="{{ $file->name }}">--}}
							</div>
						@endforeach
					</div>
				</div>
				@endif

				<div class="row listing-footer-cta hide-print">
					<div class="col-md-9">
						<h2 class="fc-color5 fw-bold ">Engaged in talks about this business?</h2>
					</div>

					<div class="col-md-3">
						<div class="text-right pt1 pb2">
							<app-create-inquiry
                            :listing="{{ $business->toJson() }}"></app-create-inquiry>
						</div>
					</div>
				</div>

                <div class="disclaimer">
                    Disclaimer: The information for this business has been provided by the Seller and/or his or her designee and has not been verified by The Firm Exchange LLC. The Firm Exchange LLC does not own a stake in the business or in its sale, and as such, makes no claims or guarantees as to the accuracy or completeness of the information provided. Please see our <a href="/terms-conditions">Terms and Conditions</a> for more information.
                </div>
			</div>


		</div>
</div>
