<template>
    <div class="">
        <button
        @click="handleConfirm">Confirmation Example</button>
        <div>
            Confirmed: {{ confirmed ? 'Yes' : 'No' }}
        </div>
    </div>
</template>

<script>
module.exports = {
    name: "styleguide-confirmation-example",

    mixins: [require("./../../../mixins/confirm.js")],

    props: {},

    data() {
        return {
            confirmId: "styleguide-confirmation-example",
            confirmContent: "Test confirmation",
            confirmed: false
        };
    },

    computed: {},

    methods: {
        confirmedCallback(confirmation) {
            this.confirmed = confirmation;
        },

        canceledCallback(confirmation) {
            this.confirmed = confirmation;
        },

        handleConfirm() {
            this.confirm(
                this.confirmId,
                this.confirmContent,
                this.confirmedCallback,
                this.canceledCallback
            );
        }
    }
};
</script>
