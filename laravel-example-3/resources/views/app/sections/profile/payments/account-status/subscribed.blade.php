@if(auth()->user()->onGracePeriod())
    @include('app.sections.profile.payments.account-status.grace-period')
@else
<app-profile-cancel-subscription
:next-payment="{{ $currentUser->subscription_payment_amount }}"
renewal-date="{{ $currentUser->subscription_renewal_date->format('m/d/Y') }}">
    <template slot="not-subscribed-content">
        @include('app.sections.profile.payments.account-status.grace-period')
    </template>
</app-profile-cancel-subscription>
@endif
