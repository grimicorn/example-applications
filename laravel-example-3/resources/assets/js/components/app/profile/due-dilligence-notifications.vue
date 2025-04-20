<template>
    <div class="">
        <input-toggle
        name="emailNotificationSettings[enable_due_diligence]"
        :value="enable"
        @change="enableChanged"
        label="Diligence Center Notifications"></input-toggle>

        <input-toggle
        v-show="enable"
        class="pl3"
        name="emailNotificationSettings[due_diligence_digest]"
        :value="digestEnable"
        label="Diligence Center Notifications - Daily Summary"></input-toggle>
    </div>
</template>

<script>
module.exports = {
    props: {
        dataEnable: {
            type: Boolean,
            default: true
        },

        dataDigestEnable: {
            type: Boolean,
            default: true
        }
    },

    data() {
        return {
            enable: this.dataEnable,
            digestEnable: this.dataDigestEnable
        };
    },

    computed: {},

    methods: {
        enableChanged({ value }) {
            this.enable = value;

            if (value) {
                this.digestEnable = this.dataDigestEnable;
            } else {
                this.digestEnable = false;
            }
        }
    }
};
</script>
