<template>
    <div class="">
        <p>Clicking 'Delete' below will permanently delete this listing.  This action cannot be undone.  If you have paid a one-time fee for a single listing, this WILL NOT trigger a refund nor permit you to create a new listing under the same payment.</p>

        <input-rating
        name="overall_experience"
        :hideColon="true"
        v-if="!surveyCompleted"></input-rating>

        <input-textual
        type="textarea"
        name="products_services"
        :hideColon="true"
        label="What other products or services would like to see incorporated?"
        v-if="!surveyCompleted"></input-textual>

        <input-textual
        type="textarea"
        name="participant_message"
        label="If you would like to send a message to participants in any of the Exchange Spaces related to this listing, please do so here"
        v-if="!surveyCompleted"></input-textual>
    </div>
</template>

<script>
export default {
    props: {
        dataSurveyCompleted: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            surveyCompleted: this.dataSurveyCompleted
        };
    },

    computed: {},

    methods: {}
};
</script>
