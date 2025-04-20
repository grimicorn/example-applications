<template>
    <div class="flash-alerts">
        <alert
        v-for="(alert, index) in alerts"
        :key="alert.message"
        :type="alert.type"
        :dismissible="alert.dismissible"
        :timeout="alert.timeout"
        @closed="alertClosed(index)">
            {{ alert.message }}
        </alert>
    </div>
</template>

<script>
let _filter = require("lodash.filter");

module.exports = {
    props: {
        inModal: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            alerts: [],
            modalOpened: false
        };
    },

    methods: {
        alertClosed(index) {
            // Remove the closed alert so it will not be re-rendered later.
            this.alerts.splice(index, 1);
        },

        add(alert) {
            // Disable form alerts for errors appearing on the page when in a modal.
            if (this.modalOpened && !this.inModal && alert.type === "error") {
                return;
            }

            this.alerts = _filter(this.alerts, ({ message }) => {
                return message !== alert.message;
            });

            this.alerts.push(alert);
        },

        remove(alert) {
            this.alerts = _filter(this.alerts, value => {
                return value.message !== alert.message;
            });
        }
    },

    mounted() {
        window.Bus.$on("flash-alert", alert => {
            this.add(alert);
        });

        window.Bus.$on("clear-alert", alert => {
            this.remove(alert);
        });

        window.Bus.$on("clear-alert-all", () => {
            this.alerts = [];
        });

        window.Bus.$on("modal-opened", () => {
            this.modalOpened = true;
        });
        window.Bus.$on("modal-closed", () => {
            this.modalOpened = false;
        });
    }
};
</script>
