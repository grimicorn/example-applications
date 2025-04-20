let base = require('settings/subscription/subscribe-stripe');

Vue.component('spark-subscribe-stripe', {
    mixins: [base, require('./../../../mixins/payment-form.js')],
});
