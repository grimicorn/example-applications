import _filter from "lodash.filter";

function removeAlert(message, alerts) {
    return _filter(alerts, alert => {
        return alert.message.trim() !== message.trim();
    });
}

let srcWatch = window.srcWatch ? window.srcWatch : {};

export default {
    user: srcWatch.user ? srcWatch.user : {},
    messages: {
        genericError: "Something went wrong please try again."
    },

    formErrors: {},

    alerts: [],

    addAlert(message, type, timeout = 0, dismissible = true) {
        let alerts = removeAlert(message, this.$store.alerts);

        // Cleanup timeout
        timeout = parseInt(timeout, 10);
        if (isNaN(timeout)) {
            timeout = 0;
        }

        // Cleanup dismissible
        if (dismissible === "false") {
            dismissible = false;
        } else if (dismissible === "true") {
            dismissible = true;
        } else if (!isNaN(dismissible)) {
            dismissible = parseInt(dismissible, 10);
            dismissible = dismissible !== 0;
        }

        alerts.unshift({
            message,
            type,
            timeout,
            dismissible
        });

        this.$store.alerts = alerts;
    },

    removeAlert(message) {
        this.$store.alerts = removeAlert(message, this.$store.alerts);
    }
};
