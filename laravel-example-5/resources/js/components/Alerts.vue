<template>
    <div
        v-if="alerts"
        class="alerts"
    >
        <alert
            v-for="(alert, index) in alerts"
            :key="`${index}:${alert.message}`"
            :data-type="alert.type"
            :data-dismissible="alert.dismissible"
            :data-timeout="alert.timeout ? alert.timeout : 0"
        >
            {{ alert.message }}
        </alert>
    </div>
</template>

<script>
import queryParameters from "utilities/query-parameters.js";

export default {
    store: ["alerts", "addAlert"],

    props: {},

    data() {
        return {};
    },

    computed: {},

    methods: {
        addFromUrl() {
            let parameters = queryParameters.get();
            queryParameters.remove(
                "alert",
                "alert_type",
                "alert_timeout",
                "alert_dismissible"
            );

            if (parameters.get("alert")) {
                this.addAlert(
                    parameters.get("alert").replace(/\+/g, " "),
                    parameters.get("alert_type", "info"),
                    parameters.get("alert_timeout", 0),
                    parameters.get("alert_dismissible", true)
                );
            }
        }
    },

    created() {
        this.addFromUrl();
    }
};
</script>
