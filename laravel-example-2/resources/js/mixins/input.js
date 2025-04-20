import _debounce from 'lodash.debounce';

export default {
    props: {
        dataLabel: {
            type: String,
            required: true
        },
        dataName: {
            type: String,
            required: true
        },
        dataId: {
            type: String
        },
        value: {},
        dataError: {
            type: String,
            default: ""
        },
        dataInstructions: {
            type: String,
            default: ""
        },
        dataPlaceholder: {
            type: String,
            default: ""
        },
        dataDisabled: {
            type: Boolean,
            default: false
        },
        dataReadonly: {
            type: Boolean,
            default: false
        },
        dataRequired: {
            type: Boolean,
            default: false
        },

        dataSaveAction: {
            type: String
        },

        dataSaveMethod: {
            type: String,
            default: 'post'
        },
    },

    methods: {
        save() {
            if (!this.shouldSave) {
                return;
            }

            this.saving = true;
            let formData = new FormData();
            let value = this.formatForSave(this.inputValue);

            formData.append(this.name, value);
            formData.append("_method", this.dataSaveMethod.toUpperCase());

            this.$http
                .post(this.dataSaveAction, formData, {
                headers: {
                    "Content-Type": "multipart/form-data"
                }
                })
                .then((response) => {
                    this.saving = false;
                    this.handleSaveSuccess(response);
                })
                .catch((error) => {
                    this.saving = false;
                    this.handleSaveFailure(error);
                });
        },

        handleSaveSuccess({ data }) {
            this.$emit('save:success', data);
        },

        handleSaveFailure({ response }) {
            this.$emit('save:error', response);

            if (response.status === 422) {
                this.error = response.data.errors[this.name][0];
                return;
            }

            this.error = `${response.status}: ${response.statusText}`;
        },

        standardizeEmptyValue(value) {
            if (value === null || typeof value === 'undefined') {
                return '';
            }

            if (value === 'undefined') {
                return '';
            }

            return value;
        },

        standardizeValue(value) {
            value = this.standardizeEmptyValue(value);

            return this.customStandardizeValue(value);
        },

        customStandardizeValue(value) {
            return value;
        },

        formatForSave(value) {
            value = this.standardizeEmptyValue(value);
            value = (value === true) ? '1' : value;
            value = (value === false) ? '0' : value;

            return this.customFormatForSave(value);
        },

        customFormatForSave(value) {
            return value;
        },

        autoSave() {
            if (!this.shouldAutoSave) {
                return;
            }

            if (this.inputValue === this.oldValue) {
                return;
            }

            this.save();
        },

        emitInput() {
            this.$emit("input", this.inputValue);
        }
    },

    data() {
        return {
            inputValue: this.standardizeValue(this.value),
            oldInputValue: this.standardizeValue(this.value),
            error: this.dataError,
            saving: false,
            shouldAutoSave: true,
            autoSaveWait: 500
        };
    },

    computed: {
        shouldSave() {
            return !!this.dataSaveAction;
        },

        instructions() {
            return this.dataInstructions;
        },

        id() {
            return this.dataId ? this.dataId : this.dataName;
        },

        name() {
            return this.dataName;
        },

        disabled() {
            if (this.saving) {
                return true;
            }

            return this.dataDisabled;
        },

        readonly() {
            return this.dataReadonly;
        }
    },

    watch: {
        value(value) {
            this.inputValue = this.standardizeValue(value);
        },

        inputValue(newValue, oldValue) {
            this.$emit("input", newValue);
            this.error = '';
            this.oldInputValue = this.standardizeValue(oldValue);
        },

        dataError(value) {
            this.error = value;
        }
    },

    mounted() {
        this.$on('input', _debounce(this.autoSave, this.autoSaveWait));
    }
};
