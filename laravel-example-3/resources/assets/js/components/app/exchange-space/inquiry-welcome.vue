<template>
    <modal
    title="Introduction"
    :auto-open="true"
    :display-button="false"
    :display-cancel="true"
    data-cancel-button-class="btn"
    cancel-label="Dismiss"
    v-if="display">
        <template>
            <div class="mx-width-600 pt3 text-center pb3">
                <p>Welcome to the Business Inquiry page. Here buyers and sellers conduct preliminary
            conversations before deciding whether to launch a more formal Exchange Space.</p>

            <p>As a seller, you will receive inquiries from various buyers who are interested in learning more about your
            business or businesses that you have listed for sale. Use this opportunity to engage with buyers before
            deciding whether to invite them into an Exchange Space. Additionally, you can click on the buyer's name
            or photo to view their profile. Finally, you'll be able to Accept the inquiry and create an Exchange
            Space, or Reject the inquiry and provide feedback to the buyer.</p>

                <p>As a buyer, this is your chance to introduce yourself to the seller. Part of the Exchange Space process
                    involves sellers sharing increasingly specific information about their business. Understandably, most
                    sellers want to better understand would-be buyers before disclosing those details. At the end of the
                    conversation, the seller will make the ultimate determination about whether to launch an Exchange Space.</p>
            </div>

            <div
            class="center mx-width-300">
                <input-checkbox
                wrap-class="hide-labels"
                name="display_inquiry_intro"
                label="Never Show this Message Again"
                data-label-class="fw-regular"
                @change="changed"></input-checkbox>
            </div>
        </template>
    </modal>

</template>

<script>
module.exports = {
    props: {
        display: {
            type: Boolean,
            default: true,
        },

        route: {
            type: String,
            required: true,
        },
    },

    data() {
        return {};
    },

    computed: {},

    methods: {
        changed() {
            window.axios.delete(this.route).then(response => {});
        },
    },
};
</script>
