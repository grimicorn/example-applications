<template>
    <div class="fe-form-submit-wrap text-center">
        <button
            type="submit"
            class="clear btn btn-color4 fe-form-submit inline-block fe-input-height"
            @click="track"
        >
            Register
        </button>
    </div>
</template>

<script>
let GoogleAnalytics = require('./../../google-analytics.js');
let FacebookAnalytics = require('./../../facebook-analytics.js');

export default {
    props: {},

    data() {
        return {};
    },

    computed: {},

    methods: {
        track() {
            GoogleAnalytics.gtagReportConversion(window.location.href);
            FacebookAnalytics.fbq('CompleteRegistration');
        },
    },
};
</script>
