<template>
    <modal
    :display-cancel="true"
    button-label="Delete User"
    button-class="btn btn-link action-item"
    modal-class="modal-content-small fz-16"
    title="Delete User Account">
        <template>
            <fe-form
            form-id="admin_delete_user_form"
            :action="deleteRoute"
            method="DELETE"
            data-challenge-label='To prevent an accidental deletion of a user, type DELETE in the box below, which will in turn enable the "Delete" button.'
            submit-label="Delete"
            submit-confirm-challenge="DELETE"
            :disabled-unload="true"
            :submit-centered="true">
            </fe-form>
        </template>
    </modal>
</template>

<script>
export default {
    props: {
        dataUserId: {
            type: Number,
            required: true
        }
    },

    data() {
        return {};
    },

    computed: {
        deleteRoute() {
            return `/dashboard/admin/user/${this.dataUserId}/destroy`;
        }
    },

    methods: {}
};
</script>
