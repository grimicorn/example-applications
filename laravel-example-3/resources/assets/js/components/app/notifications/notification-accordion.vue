<template>
    <div>
        <app-form-accordion
        id="overlay_tour_notifications_accordion_step"
        header-title="Notifications"
        class="notifications-accordion"
        :collapsible="false">
            <template slot="content">
                <app-notification-messages
                :data-all-url="dataAllUrl"
                :data-notifications="filteredNotifications"></app-notification-messages>

                <app-notification-view-all
                v-if="enableShowAllLink"
                :data-url="dataAllUrl"
                @show-all="(value) => showAll = value"></app-notification-view-all>
            </template>
        </app-form-accordion>
    </div>
</template>

<script>
let _take = require('lodash.take');

module.exports = {
    mixins: [require('./notifications.js')],

    props: {
        dataAllUrl: {
            type: String,
            default: '',
        },

        dataShowAll: {
            type: Boolean,
            default: false,
        },

        dataPreviewLimit: {
            type: Number,
            default: 10,
        },
    },

    data() {
        return {
            previewLimit: this.dataPreviewLimit,
            showAll: this.dataShowAll,
        };
    },

    computed: {
        enableShowAllLink() {
            return !this.dataShowAll && this.notifications.length > 0;
        },

        filteredNotifications() {
            if (this.showAll) {
                return this.notifications;
            }

            return _take(this.notifications, this.previewLimit);
        },
    },

    methods: {},
};
</script>
