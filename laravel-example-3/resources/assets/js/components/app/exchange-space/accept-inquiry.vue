<template>
    <fe-form
    form-id="exchange_space_accept_inquiry_form"
    :action="route"
    method="POST"
    class="exchange-space-accept-inquiry-form"
    submit-class="fz-14 width-100 btn-color5"
    submit-label="Accept"
    :confirm-message="confirmationMessage"
    v-if="isSeller"
    :submit-input-height="false">
    </fe-form>
</template>

<script>
module.exports = {
    name: "app-exchange-space-accept-inquiry",

    props: {
        inquiry: {
            type: Object,
            required: true
        }
    },

    data() {
        return {};
    },

    computed: {
        route() {
            return `/dashboard/business-inquiry/${this.inquiry.id}/acceptance`;
        },

        confirmationMessage() {
            return `<p>Accepting a Business Inquiry will launch an Exchange Space between you and the buyer. From there, you'll be able to access the Diligence Center, share documents, and invite other members to the Exchange Space.</p>

                    <p>As the seller, you retain complete control of the process. At any time, you can close the Exchange Space, terminating negotiations with this buyer.</p>

                    <p>If you wish to proceed, click "Confirm" below.</p>

                `;
        },

        isSeller() {
            return this.inquiry.current_member.is_seller;
        }
    },

    methods: {}
};
</script>
