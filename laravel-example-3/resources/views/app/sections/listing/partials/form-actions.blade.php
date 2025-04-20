@php
    $publishedListings = DB::select('select * from listings where user_id = ' . $currentUser->id . ' and published = 1', [1]);
@endphp

@section('form-action-bar-left')
    <app-listing-publish-status-label
        :data-published="{{ $listing->published ? 'true' : 'false'}}"
    ></app-listing-publish-status-label>
@endsection

@section('form-action-bar-right')
    @if($listing->id)

        <app-listing-edit-button
            :data-should-display-encouragement-modal="{{ $listing->should_display_encouragement_modal ? 'true' : 'false' }}"
            :data-listing-id="{{ $listing->id }}"
        ></app-listing-edit-button>
    @else
        <app-model-save-button isLink="false"></app-model-save-button>
    @endif


    <div id="overlay_tour_listing_action_buttons" class="flex">
        @isset($previewRoutes)
            <app-model-preview-button
                store-route="{{ $previewStoreRoute }}"
                :routes="{{ json_encode($previewRoutes ?? []) }}"
                form-id="application-{{ $section }}-{{ $type }}"
                class="mr2"
                data-label="{{ $previewStoreLabel ?? 'Preview'}}"
                :data-sync-on-update="{{ ($previewSyncOnUpdate ?? true) ? 'true' : 'false' }}"
            ></app-model-preview-button>
        @endisset
        @if($currentUser->current_billing_plan === "monthly-99" && count($publishedListings) === 3)
            <button
            type="submit"
            class="btn Open model-publish-button"
            disabled
            >Post Business</button>
        @elseif(isset($enablePublish) and $enablePublish)
            <app-listing-publish-modal
            :listing="{{ json_encode($listing) }}"
            :auto-open="{{ (isset($enablePublishModal) and $enablePublishModal) ? 'true' : 'false' }}">
                <template slot="monthly-form">
                    @include('app.sections.profile.payments.subscribe-form', [
                        'planId' => \App\Support\User\BillingTransactionType::getPlanId(
                            \App\Support\User\BillingTransactionType::MONTHLY_SUBSCRIPTION
                        ),
                    ])
                </template>

                <template slot="monthly-form-small">
                    @include('app.sections.profile.payments.subscribe-form', [
                        'planId' => \App\Support\User\BillingTransactionType::getPlanId(
                            \App\Support\User\BillingTransactionType::MONTHLY_SUBSCRIPTION_SMALL
                        ),
                    ])
                </template>

                <template slot="per-listing-form">
                    @include('app.sections.profile.payments.update-form', [
                        'disabled_success_alert' => true,
                        'update_form' => false,
                    ])
                </template>
            </app-listing-publish-modal>
        @endif
    </div>
@endsection

@section('form-action-bar-bottom')
    @if($currentUser->current_billing_plan === "monthly-99" && count($publishedListings) === 3)
        Thou shalt not pass
    @else
        <app-listing-publish-status-message
            :data-published="{{ $listing->published ? 'true' : 'false'}}"
            :listing="{{ json_encode($listing) }}"
        ></app-listing-publish-status-message>
    @endif
@endsection
