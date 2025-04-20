<template>
    <modal
    button-label="Reject"
    title="Reject Business Inquiry"
    button-class="fz-14 width-100 btn-color7"
    :auto-open="autoOpen"
    v-if="isSeller">
        <template scope="props">
            <fe-form
            form-id="exchange_space_reject_inquiry_form"
            :action="route"
            method="DELETE"
            :remove-submit="true"
            class="exchange-space-reject-inquiry-form">
                <p>Rejecting a Business Inquiry will end this conversation and will not create an Exchange Space. This should only be done when you are confident that further discussions with the buyer will not be fruitful.</p>
                <p>If you wish to reject the business inquiry, please indicate your reasoning and then click on "Reject" below.</p>
                <p>This action cannot be undone.</p>

                <input-select wrap-class="hide-labels"
                name="reason"
                validation="required"
                :options="options"
                placeholder="Select Reason"></input-select>

                <input-textual wrap-class="hide-labels"
                type="textarea"
                name="explanation"
                placeholder="Please provide additional details."></input-textual>

                <input type="hidden" name="reject_inquiry_id" :value="inquiry.id">

                <div class="text-center">
                    <button
                    @click="props.close"
                    class="btn-color7 ml1 mr1" type="button" name="button">Cancel</button>

                    <button class="btn ml1 mr1" type="submit" name="button">Reject</button>
                </div>
            </fe-form>
        </template>
    </modal>
</template>

<script>
module.exports = {
    name: "app-exchange-space-reject-inquiry",

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

        options() {
            return [
                "Insufficient information in profile",
                "Mismatch based on profile",
                "Business Removed",
                "Requesting additional information before proceeding",
                "Other"
            ];
        },

        oldRejectInquiryId() {
            return this.spark.old
                ? parseInt(this.spark.old.reject_inquiry_id, 10)
                : 0;
        },

        autoOpen() {
            return this.oldRejectInquiryId === this.inquiry.id;
        },

        isSeller() {
            return this.inquiry.current_member.is_seller;
        }
    },

    methods: {}
};
</script>
