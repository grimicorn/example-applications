<template>
    <modal
    :display-cancel="true"
    button-label="Delete Exchange Space <strong class='fa fa-times' aria-hidden='true'></strong>"
    button-class="btn btn-link"
    modal-class="modal-content-medium"
    title="Delete Exchange Space">
        <template>
            <p>
                Clicking "Delete" below will remove you from this Exchange Space and close it for all participants.  It will no longer appear in your list of Exchange Spaces.
            </p>

            <fe-form
            form-id="delete-exchange-space-form"
            :action="deleteRoute"
            method="DELETE"
            data-challenge-label='To prevent an accidental deletion of an Exchange Space, type DELETE in the box below, which will in turn enable the "Delete" button.'
            submit-label="Delete"
            submit-confirm-challenge="DELETE"
            :disabled-unload="true"
            :submit-centered="true">
                <input-textual
                type="textarea"
                name="close_message"
                label="If you would like to send a message to the other participants in the Exchange Space, please do so here"></input-textual>
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
        }
    },

    data() {
        return {};
    },

    computed: {},

    methods: {}
};
</script>
