<template>
    <modal
    :button-disabled="dataDisabled"
    :display-cancel="true"
    button-label="Close Account"
    button-class="btn"
    modal-class="modal-content-medium"
    title="Close Account">
        <template>
            <p>
                Closing your account cannot be undone.  You can rejoin Firm Exchange at any time, but your current preferences, saved searches, favorites, and notifications will be permanently lost.
            </p>

            <fe-form
            form-id="delete-exchange-space-form"
            :action="deleteRoute"
            method="DELETE"
            data-challenge-label='To prevent accidentally closing your account, type CLOSE in the box below, which will in turn enable the "Close" button.'
            submit-label="Close"
            submit-confirm-challenge="CLOSE"
            :disabled-unload="true"
            :submit-centered="true">
            </fe-form>
        </template>
    </modal>
</template>

<script>
export default {
    props: {
        deleteRoute: {
            type: String,
            required: true
        },

        dataDisabled: {
            type: Boolean,
            default: true
        }
    },

    data() {
        return {};
    },

    computed: {},

    methods: {}
};
</script>
