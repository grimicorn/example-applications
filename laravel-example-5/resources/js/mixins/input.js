import _snakeCase from "lodash.snakecase";
import collect from "collect.js";

export default {
    store: ["formErrors"],

    props: {
        dataLabel: {
            type: String,
            default: ""
        },

        dataName: {
            type: String,
            required: true
        },

        dataId: {
            type: String,
            default: ""
        },

        dataPlaceholder: {
            type: String,
            default: ""
        },

        dataError: {
            type: String,
            default: ""
        },

        dataInstructions: {
            type: String,
            default: ""
        },

        dataInputClass: {
            type: [String, Array, Object],
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

        dataDusk: {
            type: String
        },

        dataAutofocus: {
            type: Boolean,
            default: false
        },

        dataRequired: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            name: this.dataName,
            placeholder: this.dataPlaceholder,
            inputClass: this.dataInputClass,
            instructions: this.dataInstructions,
            formId: null,
            errors: collect([]),
            error: this.dataError,
            autofocus: this.dataAutofocus,
            required: this.dataRequired
        };
    },

    computed: {
        label() {
            if (this.required) {
                return `${this.dataLabel}*`;
            }

            return this.dataLabel;
        },

        id() {
            if (this.dataId) {
                return this.dataId;
            }

            return _snakeCase(this.name);
        },

        dusk() {
            if (this.dataDusk) {
                return this.dataDusk;
            }

            return _snakeCase(`${this.formId}_${this.name}`);
        },

        disabled() {
            return this.dataDisabled;
        },

        readonly() {
            return this.dataReadonly;
        }
    },

    methods: {
        emitInput($event, value = null) {
            this.$emit("input", value === null ? this.value : value);
        },

        handleKeyup($event) {
            this.$emit("keyup", $event);
        },

        clearErrors() {
            this.errors = collect([]);
            this.error = "";
        },

        setFormId() {
            return new Promise((resolve, reject) => {
                let input = this.$el.querySelector(`#${this.id}`);
                if (input && input.form) {
                    this.formId = input.form.id;
                    resolve(this.formId);
                }
            });
        },

        setErrors(errors) {
            if (errors) {
                this.errors = errors.get(this.id, collect([]));
                this.error = this.errors.first();

                return;
            }

            this.clearErrors();
        }
    }
};
