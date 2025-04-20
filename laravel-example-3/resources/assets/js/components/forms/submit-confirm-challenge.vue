<template>
    <div
    v-if="dataChallenge"
    class="submit-confirm-challenge">
        <!-- Label -->
        <input-label
        :label-class="['fe-input-label text-left']"
        :label="challengeLabel"></input-label>

        <div class="clearfix inline-block text-left">
            <input
            type="text"
            class="mr1 inline pa1 fe-input fe-input-height mb2 pull-left text-center"
            :value="challengeValue.toUpperCase()"
            @input="challengeValue = $event.target.value.toUpperCase()"
            :size="challengeValueSize"
            :disabled="challengeValidated">
            <!--removed placeholder="challenge"-->

            <br>

            <button
            type="submit"
            class="clear"
            :class="submitButtonClass"
            :disabled="submitDisabled || !challengeValidated"
            v-text="submitLabel"></button>
        </div>
    </div>

    <div v-else>
        <button
        type="submit"
        class="clear"
        :class="submitButtonClass"
        :disabled="submitDisabled"
        v-text="submitLabel"></button>
    </div>
</template>

<script>
export default {
    props: {
        submitClass: {
            type: String,
            default: "btn btn-color4"
        },

        submitInputHeight: {
            type: Boolean,
            default: true
        },

        submitLabel: {
            type: String,
            default: "Submit"
        },

        submitDisabled: {
            type: Boolean,
            default: false
        },

        dataChallenge: {
            type: String,
            required: true
        },

        dataLabel: {
            type: String,
            default: ""
        }
    },

    data() {
        return {
            challengeValue: "",
            challengeValidated: this.dataChallenge ? false : true,
            challenge: this.dataChallenge.toUpperCase()
        };
    },

    computed: {
        challengeValueSize() {
            return this.dataChallenge.length + 2;
        },

        submitButtonClass() {
            return [
                this.submitClass,
                "inline-block",
                this.submitInputHeight ? "fe-input-height" : ""
            ].join(" ");
        },

        challengeLabel() {
            if (!this.dataLabel) {
                return `Type ${this.challenge} to enable submission.`;
            }

            return this.dataLabel;
        }
    },

    methods: {
        validateChallenge() {
            let value = this.challengeValue.toUpperCase();
            let challenge = this.challenge.toUpperCase();

            if (value === challenge) {
                this.challengeValidated = true;
                this.$emit("valid");
            } else {
                this.$emit("invalid");
            }
        }
    },

    watch: {
        challengeValue() {
            this.validateChallenge();
        }
    }
};
</script>
