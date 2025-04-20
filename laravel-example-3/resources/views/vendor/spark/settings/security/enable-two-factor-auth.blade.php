<spark-enable-two-factor-auth :user="user" inline-template>
    <div class="enable-two-factor-auth clearfix">
        {{--  Information Message --}}
        <alert :dismissible="false">
            With two-factor authentication enabled, login tokens can be sent to your device via SMS (text message). However, we recommend that you download the <strong><a href="https://authy.com" target="_blank">Authy</a></strong> app on your smartphone for an enhanced level of security. The Authy app is available for iOS and Android.

            Once you have entered and confirmed your mobile number, clicking "Enable" will activate two-factor authorization on your device. It is important that you write down and store the provided reset code. This code will allow you to access your account in the event you lose access to your two-factor-connected device.
        </alert>

        <app-profile-enable-two-factor-form
        :data-country-code-error="getFormError('country_code')"
        :data-phone-error="getFormError('phone')"
        :data-form="form"
        @submit="enable"
        @change:country-code="handleCountryCodeChange"
        @change:phone="handlePhoneNumberChange"
        @change:phone-verify="handlePhoneNumberVerifyChange"></app-profile-enable-two-factor-form>
    </div>
</spark-enable-two-factor-auth>
