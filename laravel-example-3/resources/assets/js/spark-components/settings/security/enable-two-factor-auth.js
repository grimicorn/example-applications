let base = require('settings/security/enable-two-factor-auth');

Vue.component('spark-enable-two-factor-auth', {
    mixins: [base],

    methods: {
        handleCountryCodeChange(input) {
            this.form.country_code = input.value;
        },

        handlePhoneNumberChange(input) {
            this.form.phone = input.value;
        },

        handlePhoneNumberVerifyChange(input) {
            this.form.phone_verify = input.value;
        },

        getFormError(field) {
            let error = this.form.errors.get(field);

            return typeof error === 'undefined' ? '' : error;
        },
    },
});
