<template>
    <div>
        <modal
        :button-label="buttonLabel"
        :button-class="buttonClass"
        :title="modalTitle"
        :auto-open="autoOpen"
        :display-cancel="true"
        :cancel-label="cancelLabel"
        @closed="reset">
            <template scope="props">
                <div
                v-if="displayChoices"
                class="text-center mt3 mb3">
                    <div class="nowrap mb3">
                        Are you deleting this listing because the business has been sold?
                    </div>

                    <button @click="businessSold = true" class="mr1">Yes</button>
                    <button @click="businessSold = false">No</button>
                </div>

                <fe-form
                v-else
                form-id="listing_exit_survey_form"
                :action="submitRoute"
                :method="method"
                class="mt3"
                :disabled-unload="true"
                :submit-centered="true"
                :submit-label="submitLabel"
                :submit-confirm-challenge="submitConfirmChallenge"
                :data-challenge-label="challengeLabel">
                    <app-listing-exit-survey-business-sold
                    :data-use-initial-close-content="dataUseInitialCloseContent"
                    :data-survey-completed="surveyCompleted"
                    :data-is-deal-close="isDealClose"
                    :data-is-space-close="isSpaceClose"
                    v-if="businessSold"></app-listing-exit-survey-business-sold>

                    <app-listing-exit-survey-business-not-sold
                    :data-survey-completed="surveyCompleted"
                    v-else></app-listing-exit-survey-business-not-sold>

                    <input
                    type="hidden"
                    name="disable_space_close"
                    value="1"
                    v-if="dataDisableSpaceClose">
                </fe-form>
            </template>
        </modal>
    </div>
</template>

<script>
export default {
    props: {
        dataDisableSpaceClose: {
            type: Boolean,
            default: false,
        },

        dataUseInitialCloseContent: {
            type: Boolean,
            default: false,
        },

        dataSubmitRoute: {
            type: String,
            required: true,
        },

        dataBusinessSold: {
            type: Boolean,
            default: undefined,
        },

        dataAutoOpen: {
            type: Boolean,
            default: false,
        },

        dataButtonLabel: {
            type: String,
            default: '',
        },

        dataUseButtonLink: {
            type: Boolean,
            default: true,
        },

        dataIsDealClose: {
            type: Boolean,
            default: false,
        },

        dataIsSpaceClose: {
            type: Boolean,
            default: false,
        },

        dataExitSurvey: {
            type: Object,
        },
    },

    data() {
        return {
            submitRoute: this.dataSubmitRoute,
            businessSold: this.dataBusinessSold,
            autoOpen: this.dataAutoOpen,
            useButtonLink: this.dataUseButtonLink,
            isDealClose: this.dataIsDealClose,
            isSpaceClose: this.dataIsSpaceClose,
            exitSurvey: this.dataExitSurvey,
        };
    },

    computed: {
        cancelLabel() {
            return this.isDealClose ? 'Dismiss' : 'Cancel';
        },

        surveyCompleted() {
            return !!this.exitSurvey;
        },

        method() {
            return this.isDealClose ? 'POST' : 'DELETE';
        },

        buttonLabel() {
            // Allow for manually overriding
            if (this.dataButtonLabel) {
                return this.dataButtonLabel;
            }

            // If not manually overriden then set based on its state.
            let label = this.businessSold ? 'Close Business' : 'Delete Business';

            if (this.dataUseButtonLink) {
                label =
                    label +
                    ' <strong aria-hidden="true" class="fa fa-times"></strong>';
            }

            return label;
        },

        buttonClass() {
            return this.dataUseButtonLink ? 'btn btn-link' : 'btn';
        },

        modalTitle() {
            let title = this.businessSold ? 'Close Business' : 'Delete Business';
            title = this.isDealClose ? 'Congratulations!' : title;

            return title;
        },

        displayChoices() {
            return this.businessSold === undefined;
        },

        submitConfirmChallenge() {
            // Disable challenge for listing closed
            if (this.isDealClose) {
                return '';
            }

            return this.businessSold ? 'Close' : 'Delete';
        },

        submitLabel() {
            let label = this.businessSold ? 'Close Business' : 'Delete';
            label = this.isDealClose ? 'Submit Survey' : label;

            return label;
        },

        challengeLabel() {
            if (this.businessSold) {
                return 'To prevent accidental closure of businesses, please type CLOSE in the box below, which will in turn enable the Close Business button.';
            }

            return 'To prevent an accidental deletion of a business, type DELETE in the box below, which will in turn enable the "Delete" button.';
        },
    },

    methods: {
        reset() {
            this.businessSold = this.dataBusinessSold;
        },
    },
};
</script>
