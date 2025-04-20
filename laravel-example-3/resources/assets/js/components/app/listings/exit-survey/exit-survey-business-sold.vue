<template>
    <div>
        <div v-if="dataIsDealClose">
            <p>
                Congratulations on completing the sale of this business!
            </p>

            <p>
                To help us improve our service, we ask that you answer the questions below.  Please note that the survey is completely optional and that answers are not linked to you or your account.
            </p>
        </div>

        <div v-else-if="isSpaceClose">
            <p>
                You will now remove your listing from the site and close all related Exchange Spaces.  The removal of the listing is permanent and cannot be undone.  Therefore, we encourage you and all other participants to export any files or conversations that you wish to retain.
            </p>

            <p>
                We strongly urge you to coordinate with the members of this Exchange Space to make sure all parties have whatever information they need.  The closing of your transaction may depend on records currently stored in this Exchange Space.
            </p>
        </div>

        <div v-else>
            <p>
                {{ closeContent }}  We will now remove your listing from the site and close all related Exchange Spaces.  The removal of the listing is permanent and cannot be undone.  Therefore, we encourage you and all other participants to export any files or conversations that you wish to retain.
            </p>

            <p v-if="!surveyCompleted">To help us improve our service, we ask that you answer the questions below.  Please note that the survey is completely optional and that answers are not linked to you or your account.</p>
        </div>

        <input-textual
        type="price"
        name="final_sale_price"
        :hideColon="true"
        label="What was the final sale price of this business?"
        v-if="!surveyCompleted && !isSpaceClose"></input-textual>

        <input-rating
        name="overall_experience"
        :hideColon="true"
        v-if="!surveyCompleted && !isSpaceClose"></input-rating>

        <input-textual
        type="textarea"
        name="products_services"
        :hideColon="true"
        label="What other products or services would like to see incorporated?"
        v-if="!surveyCompleted && !isSpaceClose"></input-textual>

        <input-textual
        type="textarea"
        name="participant_message"
        label="If you would like to send a message to participants in any of the Exchange Spaces related to this listing, please do so here"
        v-if="(!dataIsDealClose && !surveyCompleted) || dataIsSpaceClose"></input-textual>
    </div>
</template>

<script>
export default {
    props: {
        dataUseInitialCloseContent: {
            type: Boolean,
            default: false,
        },

        dataSurveyCompleted: {
            type: Boolean,
            default: false,
        },

        dataIsDealClose: {
            type: Boolean,
            default: false,
        },

        dataIsSpaceClose: {
            type: Boolean,
            default: false,
        },
    },

    data() {
        return {
            surveyCompleted: this.dataSurveyCompleted,
            isSpaceClose: this.dataIsSpaceClose,
        };
    },

    computed: {
        closeContent() {
            if (this.dataUseInitialCloseContent) {
                return 'Congratulations on completing the sale of this business.';
            }

            return 'Congratulations again on completing the sale of this business.';
        },
    },

    methods: {},
};
</script>
