let _map = require('lodash.map');
let _filter = require('lodash.filter');
let _includes = require('lodash.includes');
let striptags = require('striptags');

module.exports = {
    props: {
        type: {
            type: String,
            default: 'text',
        },

        name: {
            type: String,
            required: true,
        },

        id: {
            type: String,
            default: '',
        },

        label: {
            type: String,
            default: '',
        },

        placeholder: {
            type: String,
            default: '',
        },

        wrapClass: {
            type: String,
            default: '',
        },

        inputClass: {
            type: String,
            default: '',
        },

        value: {
            default: '',
        },

        validationMessage: {
            type: String,
            default: '',
        },

        validation: {
            type: String,
            default: '',
        },

        parentFormId: {
            type: String,
            default: '',
        },

        inputDisabled: {
            type: Boolean,
            default: false,
        },

        inputReadonly: {
            type: Boolean,
            default: false,
        },

        inputMaxlength: {
            type: Number,
            default: -1,
        },

        inputRows: {
            type: [String, Number],
            default: '5',
        },

        tooltip: {
            type: String,
            default: '',
        },

        disableDefaultOld: {
            type: Boolean,
            default: false,
        },

        hideColon: {
            type: Boolean,
            default: false,
        },

        dataIgnoreDirtyCheck: {
            type: Boolean,
            default: false,
        },

        dataDisableErrorMessageCleanup: {
            type: Boolean,
            default: false,
        },

        dataLabelClass: {
            type: String,
            default: '',
        },
    },

    data() {
        return {
            classPrefix: 'fe-input',
            inputValue: '',
            serverValidationMessage: this.validationMessage,
            originalValue: this.formatInputValue(this.value),
            ignoreDirtyCheck: this.dataIgnoreDirtyCheck,
        };
    },

    computed: {
        isDirty() {
            if (this.ignoreDirtyCheck) {
                return false;
            }

            let inputValue = this.formatInputValue(this.inputValue);
            let originalValue = this.formatInputValue(this.originalValue);

            // Handle booleans
            if (
                typeof inputValue === 'boolean' ||
                inputValue === 'on' ||
                inputValue === 'off'
            ) {
                inputValue = inputValue === 'on' ? true : inputValue;
                inputValue = inputValue === 'off' ? false : inputValue;
                inputValue = !!inputValue;
                originalValue = originalValue === 'on' ? true : originalValue;
                originalValue = originalValue === 'off' ? false : originalValue;
                originalValue = !!originalValue;
            }

            // Handle arrays
            if (typeof inputValue === 'object') {
                inputValue = JSON.stringify(inputValue);
                originalValue = !originalValue ? [] : originalValue;
                originalValue = JSON.stringify(originalValue);
            }

            return originalValue !== inputValue;
        },

        isRequired: {
            get() {
                return _includes(this.validation, 'required');
            },

            set() {},
        },

        hasErrors: {
            get() {
                return !!this.errorMessage;
            },

            set() {},
        },

        validationRules: {
            get() {
                let validation = this.validation;

                if (this.inputMaxlength > 0) {
                    validation = this.appendValidationRule(
                        `max:${this.inputMaxlength}`,
                        this.validation
                    );
                }

                return validation;
            },
            set() {},
        },

        inputId() {
            return this.id === '' ? this.name : this.id;
        },

        inputClasses() {
            return [
                this.inputClass,
                `${this.classPrefix}-${this.name}`,
                this.classPrefix,
                this.inputDisabled ? 'is-disabled' : '',
                this.inputReadonly ? 'is-readonly' : '',
            ];
        },

        inputPlaceholder() {
            return this.placeholder ? this.placeholder : this.label;
        },

        labelClass() {
            return [
                `${this.classPrefix}-${this.name}-label`,
                'fe-input-label',
                this.dataLabelClass,
            ];
        },

        inputWrapClass() {
            return [
                `${this.classPrefix}-wrap`,
                `${this.classPrefix}-${this.name}-wrap`,
                `${this.wrapClass}`.trim(),
                'clearfix',
                this.hasErrors ? 'has-error' : '',
                this.tooltip ? 'has-tooltip' : '',
            ];
        },

        inputLabel() {
            if (!this.label) {
                return '&nbsp;';
            }

            if (this.label && this.hideColon) {
                return `${this.label}`;
            }

            return this.label && this.isRequired
                ? `${this.label}*:`
                : `${this.label}:`;
        },

        oldValue() {
            let oldValues =
                typeof window.Spark.old === 'object' ? window.Spark.old : {};
            let value =
                typeof oldValues[this.name] === 'undefined'
                    ? ''
                    : oldValues[this.name];

            return value;
        },

        inputError() {
            let errors =
                typeof window.Spark.errors === 'object'
                    ? window.Spark.errors
                    : {};
            errors =
                typeof errors[this.name] === 'undefined'
                    ? []
                    : errors[this.name];

            return errors.length === 0 ? '' : errors[0];
        },

        errorMessage: {
            get() {
                let message = '';

                if (this.errors.has(this.name)) {
                    message = this.errors.first(this.name);
                } else if (this.serverValidationMessage) {
                    message = this.serverValidationMessage;
                } else if (this.errors.items.length > 0) {
                    message = this.errors.items[0].msg;
                } else {
                    message = this.inputError;
                }

                // Try to cleanup the error message if needed.
                message = this.cleanupErrorMessage(message);

                return message;
            },

            set() {},
        },

        formId() {
            return this.getFormId();
        },
    },

    methods: {
        cleanupErrorMessage(message) {
            if (this.dataDisableErrorMessageCleanup) {
                return message;
            }

            // Try to clean up the error message.
            message = message.replace(this.name, '');
            message = striptags(message.replace(/_/g, ' '));

            // Odd edge case request for terms of service.
            message = message.replace(
                'The I Accept The Terms Of Service field is required',
                'Accepting the Terms of Service is required'
            );

            return message;
        },

        eventData() {
            return {
                value: this.inputValue,
                hasErrors: this.hasErrors,
                name: this.name,
                isRequired: this.isRequired,
                inputType: this.$options.name,
                files: typeof this.files === 'undefined' ? [] : this.files,
                isDirty: this.isDirty,
            };
        },

        getDefaultInputValue() {
            if (this.oldValue && !this.disableDefaultOld) {
                return this.formatInputValue(this.oldValue);
            }

            return this.formatInputValue(this.value);
        },

        formatInputValue(value) {
            if (
                value === null ||
                value === 'null' ||
                value === '' ||
                typeof value === 'undefined'
            ) {
                return '';
            }

            if (value === true) {
                return 'on';
            }

            if (value === false) {
                return 'off';
            }

            return value;
        },

        emitChange(name) {
            this.$emit('change', this.eventData());
            this.$emit('input', this.eventData().value);
            window.Bus.$emit(`${this.formId}.change`, this.eventData());
        },

        getFormId() {
            let parent = this.$parent;
            let count = 0;
            let formId = '';

            while (typeof parent !== 'undefined' && count < 5) {
                if (typeof parent.formId !== 'undefined') {
                    formId = parent.formId;

                    break;
                }

                parent = parent.$parent;
                count = count + 1;
            }

            return formId;
        },

        emitMounted() {
            Vue.nextTick(() => {
                window.Bus.$emit(
                    `${this.formId}.input.mounted`,
                    this.eventData()
                );
            });
        },

        handleChange() {
            this.emitChange();
        },

        convertInputsProp(inputs) {
            return _map(inputs, function(value) {
                if (typeof value === 'object') {
                    return value;
                }

                return {
                    label: value,
                    value: value,
                };
            });
        },

        appendValidationRule(rule, rules) {
            // Only add the rule once.
            if (-1 !== rules.indexOf(rule)) {
                return;
            }

            rules = rules.split('|');
            rules.push(rule);

            rules = _filter(rules, function(rule) {
                return rule;
            });

            return rules.join('|');
        },

        reset() {
            this.inputValue = '';
        },

        handleFormValidationError(name) {
            name = name === undefined ? this.name : name;
            name = name
                .replace(/\[]/gm, '')
                .replace(/['"\]]/gm, '')
                .replace(/\[/gm, '.');
            window.Bus.$on(`${this.formId}.validation-error`, (errors) => {
                if (typeof errors[name] !== 'undefined') {
                    this.serverValidationMessage = errors[name][0];
                }
            });
        },
    },

    watch: {
        inputValue(value) {
            this.emitChange();
            this.serverValidationMessage = '';
        },

        value(value) {
            this.inputValue = value;
        },

        hasErrors(value) {
            this.emitChange();

            if (value) {
                window.Bus.$emit(`${this.name}:invalid-input`);
            } else {
                window.Bus.$emit(`${this.name}:valid-input`);
            }
        },
    },

    mounted() {
        this.inputValue = this.getDefaultInputValue();

        let form = document.getElementById(this.formId);
        if (form) {
            document.addEventListener('reset', this.reset);
        }

        this.emitMounted();
        this.handleFormValidationError();
    },
};
