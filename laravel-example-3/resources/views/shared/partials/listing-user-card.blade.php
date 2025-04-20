@if($business->display_listed_by)
<div class="border-prime pl2 pr2 pt2 pb2">
	<div class="row prof-result-card">
		<div class="col-xs-4 col-sm-3 prof-result-photo hide-mobile print-25">
			<a href="{{$business->user->profile_url}}">
				<avatar
                src="{{ $business->user->photo_roll_url }}"
                width="250"
                height="250"
                initials="{{ $business->user->initials }}"></avatar>
			</a>
		</div>

		<div class="col-sm-9 print-75 print-pull-right">
			<div class="row">
				 <div class="col-xs-12 print-75">
					<div class="row">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-3 prof-result-photo hide-desktop">
										<a href="{{ $business->user->profile_url}}">
											<avatar
											src="{{ $business->user->photo_roll_url }}"
											width="250"
											height="250"
											initials="{{ $business->user->initials }}"></avatar>
										</a>
									</div>
									<div class="col-xs-9 col-sm-12">
										<h2 class="mb1">
											<a
											class="a-nd a-color4"
											href="{{$business->user->profile_url}}">
													{{$business->user->name}}
											</a>
										</h2>
										<h3 class="mb1">{{$business->user->professionalInformation->occupation}}</h3>
									</div>
								</div>
							</div>
							<div class="col-xs-12">
								<p class="mb2 pb2 bb1">{{$business->user->tagline}}</p>
							</div>

							@if($business->user->professionalInformation->company_name)
							<div class="col-xs-12 ">
								 <h2 class="mb1">{{$business->user->professionalInformation->company_name}}</h2>
							</div>
							@endif

							@if($business->user->professionalInformation->areas_served_list)
							<div class="col-xs-12">
								 <span class="h3 section-content-label fz-16">States Served:&nbsp;</span> <span class="section-content-value">{!! comma_separated($business->user->professionalInformation->unabbreviated_areas_served_list) !!}</span>
							</div>
							@endif

							@if($business->user->professionalInformation->license_qualifications)
							<div class="col-xs-12">
								 <span class="h3 section-content-label fz-16">Professional Designations:</span> <span>{{ implode(', ', $business->user->professionalInformation->license_qualifications) }}</span>
							</div>
							@endif
					</div>
				 </div>
			</div>
		</div>
	</div>
</div>
@endif
