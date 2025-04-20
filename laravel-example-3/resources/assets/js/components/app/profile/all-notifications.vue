<template>
    <div class="">
        <input-toggle
                class="pb3"
                name="emailNotificationSettings[enable_all]"
                :value="enable"
                @change="enableChanged"
                label="All Other Notifications"></input-toggle>

        <div v-show="!enable">
            Please note that, despite this selection, a limited number of notifications must still be sent via e-mail.
            This is limited to community-wide notifications (e.g. planned outages) and times when communications with a
            counter-party have been severed (e.g. an Exchange Space has been closed or a Business Inquiry has been rejected).
        </div>
    </div>
</template>

<script>
    module.exports = {
        props: {
            dataEnable: {
                type: Boolean,
                default: true,
            },
        },

        data() {
            return {
                enable: this.dataEnable,
            };
        },

        computed: {},

        methods: {
            enableChanged({value}) {
                this.enable = value;
            }
        },
    };
</script>
