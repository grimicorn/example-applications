<template>
    <div class="cancel-subscription relative">
        <loader
        :loading="canceling"></loader>

        <div
        class="mb2"
        v-if="!canceled">
            You are currently subscribed to a monthly plan.
            <br>
            Your next payment of <strong>{{ nextPayment | price }}</strong> will be on <strong><timezone-date :date="renewalDate"></timezone-date></strong>.
        </div>
        <div
        v-else
        class="mb2">
            <slot name="not-subscribed-content"></slot>
        </div>

        <button
        @click="confirmCancel"
        v-if="!canceled">Cancel</button>

        <alert
        type="success"
        :timeout="5000"
        v-else>
            Subscription canceled successfully
        </alert>
    </div>
</template>

<script>
module.exports = {
    mixins: [
        require("./../../../mixins/filters.js"),
        require("./../../../mixins/confirm.js")
    ],

    props: {
        nextPayment: {
            type: Number,
            required: true
        },

        renewalDate: {
            type: String,
            required: true
        }
    },

    data() {
        return {
            url: "/settings/subscription",
            canceling: false,
            canceled: false
        };
    },

    computed: {},

    methods: {
        confirmCancel() {
            this.canceling = true;

            this.confirm(
                "cancel-subscription",
                "Are you sure you want to cancel your subscription?",
                () => {
                    this.cancel();
                },
                () => {
                    this.canceling = false;
                }
            );
        },

        cancel() {
            window.axios
                .delete(this.url)
                .then(() => {
                    this.canceled = true;
                    this.canceling = false;
                })
                .catch(() => {
                    this.canceling = false;
                });
        }
    }
};
</script>
