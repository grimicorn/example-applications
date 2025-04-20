<spark-update-payment-method-stripe :user="user" :team="team" :billable-type="billableType" inline-template>

    <div>
            <!-- Card Update Success Message -->
            @if(!isset($disabled_success_alert) or !$disabled_success_alert)
            <alert type="success" v-if="form.successful" :timeout="5000">
                Your card has been updated.
            </alert>
            @endif

            <!-- Generic 500 Level Error Message / Stripe Threw Exception -->
            <alert type="error" v-if="form.errors.has('form')">
                We had trouble updating your card. It's possible your card provider is preventing us from charging the card. Please contact
                your card provider or customer support.
            </alert>

            <div class="row">
                <app-listing-publish-payment-form
                :update-form="{{
                    (isset($update_form) and $update_form) ? 'true' : 'false'
                }}"
                :data-card-form="cardForm"
                :form="form"
                :busy="form.busy"
                :card-form-errors="cardForm.errors.all()"
                data-card-placeholder="{{ auth()->user()->card_last_four_display }}"
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
                :form-errors="form.errors.all()"
                @form-change="formChange"
                @card-form-change="cardFormChange"
                @update="update"></app-listing-publish-payment-form>
            </div>
    </div>
</spark-update-payment-method-stripe>
