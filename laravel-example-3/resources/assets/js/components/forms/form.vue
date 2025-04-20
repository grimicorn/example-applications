<template>
    <form
    novalidate
    :id="formId"
    :method="formMethod"
    :action="action"
    class="fe-form relative"
    :class="{
        'is-submitting': this.isSubmitting,
        'test': this.hasErrors
    }"
    enctype="multipart/form-data"
    @submit="handleSubmit($event)">
        <loader :loading="isSubmitting"></loader>

        <input
        type="hidden"
        name="enable_redirect"
        value="1"
        v-if="this.enableRedirect">

        <input
        type="hidden"
        name="_token"
        :value="csrfToken">

        <input
        type="hidden"
        name="_method"
        :value="method"
        v-if="spoofMethod">

        <slot></slot>

        <div
        v-if="!removeSubmit"
        class="fe-form-submit-wrap"
        :class="{
            'text-center' : submitCentered,
            'text-left': submitLeftAligned,
            'text-right': submitRightAligned,
        }">
            <submit-confirm-challenge
            :submit-input-height="submitInputHeight"
            :submit-class="`${submitClass} fe-form-submit`"
            :submit-disabled="submitDisabled"
            :submit-label="submitLabel"
            :data-challenge="submitConfirmChallenge"
            :data-label="dataChallengeLabel"
            @valid="() => submissionChallengeCompleted = true"
            @invalid="() => submissionChallengeCompleted = false"></submit-confirm-challenge>
        </div>
    </form>
</template>
<script>
let _filter = require('lodash.filter');
let _foreach = require('lodash.foreach');
let _map = require('lodash.map');
let _replace = require('lodash.replace');

module.exports = {
    mixins: [
        require('./../../mixins/confirm.js'),
        require('./../../mixins/utilities'),
    ],

    data() {
        return {
            csrfToken: window.FE.csrfToken,
            inputs: {},
            inputNames: [],
            isDirty: false,
            isSubmitting: false,
            hasInputMultiFileUpload: false,
            inputMultiFileUploads: [],
            confirmed: this.confirmMessage === '',
            submissionChallengeCompleted: this.submitConfirmChallenge === '',
        };
    },

    props: {
        method: {
            type: String,
            default: 'POST',
        },

        action: {
            type: String,
            default: '',
        },

        formId: {
            type: String,
            required: true,
        },

        submitClass: {
            type: String,
            default: 'btn btn-color4',
        },

        submitLabel: {
            type: String,
            default: 'submit',
        },

        submitLeftAligned: {
            type: Boolean,
            default: false,
        },

        submitCentered: {
            type: Boolean,
            default: false,
        },

        submitRightAligned: {
            type: Boolean,
            default: false,
        },

        submitIgnoreErrors: {
            type: Boolean,
            default: true,
        },

        removeSubmit: {
            type: Boolean,
            default: false,
        },

        submitInputHeight: {
            type: Boolean,
            default: true,
        },

        confirmMessage: {
            type: String,
            default: '',
        },

        confirmTitle: {
            type: String,
            default: 'Confirm',
        },

        confirmSubmitLabel: {
            type: String,
            default: 'Confirm',
        },

        enableRedirect: {
            type: Boolean,
            default: false,
        },

        disabledUnload: {
            type: Boolean,
            default: false,
        },

        shouldAjax: {
            type: Boolean,
            default: false,
        },

        submitConfirmChallenge: {
            type: String,
            default: '',
        },

        dataChallengeLabel: {
            type: String,
            default: '',
        },

        dataErrorAlertValidationMessage: {
            type: String,
            default: 'Unable to save successfully. See below for errors.',
        },

        dataDisabled: {
            type: Boolean,
            default: false,
        },
    },

    computed: {
        spoofMethod() {
            let method = this.method.toUpperCase();

            return method !== 'GET' && method !== 'POST';
        },

        hasErrors() {
            let errors = _filter(this.inputNames, name => {
                return (
                    typeof this.inputs[name] !== 'undefined' &&
                    this.inputs[name].hasErrors
                );
            });

            return errors.length > 0;
        },

        submitDisabled() {
            if (this.dataDisabled) {
                return true;
            }

            return this.submitIgnoreErrors ? false : this.hasErrors;
        },

        formMethod() {
            let method = this.method.toLowerCase();

            return 'get' === method ? 'GET' : 'POST';
        },

        errorAlerts() {
            return {
                generic: 'Something went wrong please try again.',
                validation: this.dataErrorAlertValidationMessage,
            };
        },

        shouldStopUnload() {
            // Allow the unload to be disabled globally.
            if (!!window.Spark.disableUnloadConfirmation) {
                return false;
            }

            return this.isDirty && !this.isSubmitting && !this.disabledUnload;
        },
    },

    methods: {
        resetInputNames() {
            let names = this.inputNames;
            this.inputNames = [];
            this.inputNames = names;
        },

        getInputMultiFileUploads() {
            return _filter(
                this.inputs,
                input => input.inputType === 'input-multi-file-upload'
            );
        },

        handleInputMounted(input) {
            // Setup the inputs.
            this.inputs[input.name] = input;
            this.inputs[input.name].hasErrors = input.isRequired
                ? true
                : input.hasErrors;
            this.inputNames.push(input.name);

            // Check if we have any multi inputs since we will want to submit these differently.
            this.inputMultiFileUploads = this.getInputMultiFileUploads();
            this.hasInputMultiFileUpload =
                this.inputMultiFileUploads.length > 0;
        },

        handleInputChange(input) {
            if (typeof this.inputs[input.name] !== 'undefined') {
                this.updateDirtyStatus(input);
                this.inputs[input.name].hasErrors = input.hasErrors;
                this.resetInputNames();
                this.inputMultiFileUploads = this.getInputMultiFileUploads();
                this.emitFormUpdated(input);
            }
        },

        emitFormUpdated(input) {
            window.Vue.nextTick(() => {
                window.Bus.$emit(
                    `${this.formId}.updated`,
                    this.getFormData(),
                    input,
                    this.isSubmitting
                );
                this.$emit(
                    `${this.formId}.updated`,
                    this.getFormData(),
                    input,
                    this.isSubmitting
                );
            });
        },

        getFormData() {
            // Get the existing form data.
            let formData = new FormData(this.$el);

            // Fix safari empty file bug.
            if (typeof formData.entries === 'function') {
                for (var pair of formData.entries()) {
                    if (pair[1] instanceof File && pair[1].size === 0) {
                        formData.delete(pair[0]);
                    }
                }
            }

            // Append the uploaded files.
            _foreach(this.inputMultiFileUploads, input => {
                _foreach(input.files, file => {
                    if (file !== false && !file.uploaded) {
                        formData.append(`${input.name}[new][]`, file);
                    }
                });
            });

            return formData;
        },

        submitViaAjax(event) {
            if (!this.hasInputMultiFileUpload && !this.shouldAjax) {
                return;
            }

            // Stop default submission
            event.preventDefault();

            // Set the form submission
            window.axios
                .post(this.action, this.getFormData())
                .then(response => {
                    this.isDirty = false;

                    if (!this.enableRedirect) {
                        window.flashAlert(response.data.status, {
                            type: 'success',
                            timeout: 5000,
                        });
                        this.isSubmitting = false;
                    }

                    if (
                        typeof response.data.redirect === 'string' &&
                        this.enableRedirect
                    ) {
                        window.location = response.data.redirect;
                    }

                    // Set the files as uploaded
                    let uploads = this.inputMultiFileUploads;
                    uploads = _map(uploads, input => {
                        return _map(input.files, file => {
                            file.uploaded = true;
                            return file;
                        });
                    });
                    this.inputMultiFileUploads = uploads;

                    window.Bus.$emit(
                        `${this.formId}.successfully-submitted`,
                        response
                    );
                })
                .catch(this.handleSubmissionError);
        },

        handleSubmissionError(error) {
            let message = this.errorAlerts.generic;

            if (error.response.status === 422) {
                if (error.response.data.general_error === undefined) {
                    message = this.errorAlerts.validation;
                    window.Bus.$emit(
                        `${this.formId}.validation-error`,
                        error.response.data.errors
                    );
                } else {
                    message = error.response.data.general_error;
                }
            }

            window.Bus.$emit(`${this.formId}.submit-error`, error.response);

            window.flashAlert(message, {
                type: 'error',
            });
            this.isSubmitting = false;
        },

        clearErrorAlertsMessages() {
            window.clearAlertAll();
        },

        setByPath(obj, path, value) {
            var parts = path.split('.');
            var o = obj;
            if (parts.length > 1) {
                for (var i = 0; i < parts.length - 1; i++) {
                    if (!o[parts[i]]) o[parts[i]] = {};
                    o = o[parts[i]];
                }
            }

            o[parts[parts.length - 1]] = value;
        },

        cancelSubmit() {
            this.isSubmitting = false;
            event.preventDefault();
        },

        updateDirtyStatus(input) {
            this.inputs[input.name].isDirty = input.isDirty;

            let dirtyInputs = _filter(this.inputs, input => {
                return input.isDirty;
            });
            this.isDirty = dirtyInputs.length > 0;
        },

        handleSubmit(event) {
            if (!this.submissionChallengeCompleted) {
                this.cancelSubmit();
                return;
            }

            this.isSubmitting = true;

            // Handle any required confirmations.
            if (!this.confirmed) {
                // Stop submission
                event.preventDefault();

                this.confirm(
                    this.formId,
                    this.confirmMessage,
                    () => {
                        // Confirm and resubmit
                        this.confirmed = true;
                        this.$el.submit();
                    },
                    this.cancelSubmit,
                    {
                        title: this.confirmTitle,
                        submitLabel: this.confirmSubmitLabel,
                    }
                );

                return;
            }

            this.clearErrorAlertsMessages();
            this.submitViaAjax(event);
        },
    },

    mounted() {
        let vm = this;
        window.onbeforeunload = function(event) {
            if (vm.shouldStopUnload) {
                return '';
            }
        };
    },

    created() {
        window.Bus.$on(`${this.formId}.input.mounted`, this.handleInputMounted);
        window.Bus.$on(`${this.formId}.change`, this.handleInputChange);
        this.$emit(`${this.formId}.created`, this.getFormData());
        window.Bus.$on(
            `${this.formId}-preview-submission-error`,
            this.handleSubmissionError
        );
    },
};
</script>
