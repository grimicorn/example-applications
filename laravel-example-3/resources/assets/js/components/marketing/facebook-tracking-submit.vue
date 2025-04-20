<template>
    <div class="fe-form-submit-wrap">
        <button
            type="submit"
            v-text="dataLabel"
            class="clear btn fe-form-submit inline-block fe-input-height"
            :class="dataButtonClass"
            @click="track"
        ></button>
    </div>
</template>

<script>
let FacebookAnalytics = require('./../../facebook-analytics.js');

export default {
    props: {
        dataLabel: {
            type: String,
            default: 'Submit',
        },

        dataButtonClass: {
            type: String,
            default: 'btn-color4',
        },

        dataTrackingMessage: {
            type: String,
            required: true,
        }
    },

    data() {
        return {};
    },

    computed: {},

    methods: {
        track() {
            FacebookAnalytics.fbq(this.dataTrackingMessage);
        }
    },
};
</script>
