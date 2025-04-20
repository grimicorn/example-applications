<template>
    <fe-form
    class="inline-block"
    method="DELETE"
    :action="route"
    :confirm-message="confirmMessage"
    :form-id="formId"
    submit-label="Delete Message"
    submit-class="btn btn-link fc-color10 a-ul fz-14"></fe-form>
</template>

<script>
export default {
    props: {
        dataMessageId: {
            type: Number,
            required: true
        },
        dataMessageBody: {
            type: String,
            required: true
        }
    },

    data() {
        return {
            messageBody: this.dataMessageBody,
            messageId: this.dataMessageId
        };
    },

    computed: {
        route() {
            return `/dashboard/message/${this.messageId}/destroy`;
        },

        formId() {
            return `message_${this.messageId}_destroy`;
        },

        confirmMessage() {
            return [
                "Are you sure you want to delete this message?",
                `"${this.messageBody}"`
            ].join("<br>");
        }
    },

    methods: {}
};
</script>
