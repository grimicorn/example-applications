let base = require('settings/payment-method/update-payment-method-stripe');

Vue.component('spark-update-payment-method-stripe', {
    mixins: [base, require('./../../../mixins/payment-form.js')],
});
