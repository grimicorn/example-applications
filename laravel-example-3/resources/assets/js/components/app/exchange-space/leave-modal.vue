<template>
    <modal
    :display-cancel="true"
    button-label="Leave Exchange Space <strong class='fa fa-times' aria-hidden='true'></strong>"
    button-class="btn btn-link"
    modal-class="modal-content-medium"
    title="Leave Exchange Space">
        <template>
            <fe-form
            form-id="leave-exchange-space-form"
            :action="deleteRoute"
            method="DELETE"
            data-challenge-label='To prevent an accidental departure from the Exchange Space, type LEAVE in the box below, which will in turn enable the "Leave" button.'
            submit-label="Leave"
            submit-confirm-challenge="LEAVE"
            :disabled-unload="true"
            :submit-centered="true">
                <p>
                    Clicking 'Leave' below will permanently remove you from this Exchange Space.  If you are the Buyer (and not an advisor), this will also close the Exchange Space.  This action cannot be undone.
                </p>

                <input-textual
                v-if="enableExitMessage"
                type="textarea"
                name="exit_message"
                label="If you would like to send a message to the other participants in the Exchange Space, please do so here:"></input-textual>
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

        enableExitMessage: {
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
