<div class="professional-single-header clearfix">
    <h1 class="pt3 pb2 bb1 mb2 col-xs-12 pl0 pr0">{{ $professional->name }}</h1>

    <div class="row">
        <div class="col-md-4 mb2-m col-sm-6 print-pull-left">
            <avatar
            src="{{ $professional->photo_featured_url }}"
            width="350"
            height="350"
            class="professional-avatar-image-wrap"
            initials="{{ $professional->initials }}"
            alt="{{ $professional->name }}"></avatar>
        </div>

        <div class="col-md-4 col-sm-6 print-pull-right">
            <div class="professional-info">
                @if($profInfo->occupation_label)
                <span class="h2 section-content-title">Occupation:</span>
                <span class="block mt0 mb1 bb-1 mb2-m">{{ $profInfo->occupation_label }}</span>
                @endif

                @if($profInfo->company_name || $profInfo->company_logo_upload_url)
                <div class=" mb1 bb-1">
                    @if($profInfo->company_name || $profInfo->company_logo_upload_url)
                    <span class="h2 section-content-title">Company:</span>
                    <span class="block mt0 mb0 mb2-m">{{ $profInfo->company_name }}</span>
                    @endif

                    @if($profInfo->company_logo_upload_url)
                    <img
                    src="{{ $profInfo->company_logo_upload_url }}"
                    height="80"
                    alt="{{ $profInfo->company_name }} logo"
                    class="pt1 pb1 company-logo">
                    @endif
                </div>
                @endif

                @if($professional->work_phone_display)
                <span class="h2 section-content-title">Phone:</span>
                <span class="block mt0 mb1 bb-1 mb2-m">{{ $professional->work_phone_display }}</span>
                @endif

                @if($profInfo->license_qualifications)
                <span class="h2 section-content-title">Professional Designations:</span>
                <span class="block mt0 mb0">{{ comma_separated($profInfo->license_qualifications) }}</span>
                @endif
            </div>
        </div>

        <div class="col-md-4 col-sm-12 hide-print">
            @include('marketing.professionals.show.contact-form')
        </div>
    </div>




</div>
