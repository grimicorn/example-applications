<app-form-accordion
header-title="Billing Status"
class="mb3"
:collapsible="false">
    <template slot="content">
        <div class="flex mb3">
            <input-label
            label="Current Account Balance:"
            class="mr1 flex-3"></input-label>

            <div class="flex-9">
                <strong>
                <app-profile-account-balance
                :user-id="{{ $currentUser->id }}"></app-profile-account-balance></strong> of credits to apply to future payments.
            </div>
        </div>

        <div class="flex">
            <input-label
            label="Account Status:"
            class="mr1 flex-3"></input-label>

            <div class="flex-9">
            @switch($currentUser->account_status)
                @case('not-subscribed')
                    @include(
                        'app.sections.profile.payments.account-status.not-subscribed'
                    )
                    @break

                @case('subscribed')
                    @include(
                        'app.sections.profile.payments.account-status.subscribed'
                    )
                    @break

                @case('per-listing')
                    @include(
                        'app.sections.profile.payments.account-status.per-listing'
                    )
                    @break
            @endswitch
            </div>
        </div>

    </template>
</app-form-accordion>

