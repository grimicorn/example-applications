<template>
    <!-- Notifications -->
    <div
    v-if="notifications && notifications.length > 0"
    class="notification-messages">
        <app-notification-message
        v-for="notification in notifications"
        :key="notification.id"
        :notification="notification"
        @deleted="handleDeleted"></app-notification-message>
    </div>

    <!-- No Notifications -->
    <div
    v-else
    class="notification-messages">
        <div class="flex pt2 pb1 pl2 pr1 justify-center">
            <strong>No Notifications</strong>
        </div>
    </div>
</template>

<script>
let _filter = require("lodash.filter");

module.exports = {
    mixins: [require("./notifications.js")],

    props: {},

    data() {
        return {};
    },

    computed: {},

    methods: {
        handleDeleted(id) {
            this.notifications = _filter(this.notifications, notification => {
                return notification.id !== id;
            });
        }
    }
};
</script>
