<spark-subscribe-stripe
:user="user"
:team="team"
:plans="plans"
:billable-type="billableType"
inline-template>
    <div>
        <!-- Common Subscribe Form Contents -->
        {{-- @include('spark::settings.subscription.subscribe-common') --}}

        <!-- Billing Information -->
        <!-- Generic 500 Level Error Message / Stripe Threw Exception -->
        <div class="alert alert-danger" v-if="form.errors.has('form')">
            We had trouble validating your card. It's possible your card provider is preventing
            us from charging the card. Please contact your card provider or customer support.
        </div>

        <div class="row">
            <app-listing-publish-payment-form
            plan-id="{{ $planId ?? '' }}"
            data-card-placeholder="{{ auth()->user()->card_last_four_display }}"
            :update-form="{{
                (isset($update_form) and $update_form) ? 'true' : 'false'
            }}"
            :data-card-form-overrides="{{ json_encode([
                'name' => auth()->user()->card_name,
                'month' => auth()->user()->card_expiration_month,
                'year' => auth()->user()->card_expiration_year,
            ]) }}"
            :data-form-overrides="{{ json_encode([
                'address' => auth()->user()->billing_address,
                'address_line_2' => auth()->user()->billing_address_line_2,
                'city' => auth()->user()->billing_city,
                'state' => auth()->user()->billing_state,
                'zip' => auth()->user()->billing_zip,
            ]) }}"
            :cardForm="cardForm"
            :form="form"
            :busy="form.busy"
            :card-form-errors="cardForm.errors.all()"
            :form-errors="form.errors.all()"
            @form-change="formChange"
            @card-form-change="cardFormChange"
            @update="subscribe"></app-listing-publish-payment-form>
        </div>
    </div>
</spark-subscribe-stripe>
