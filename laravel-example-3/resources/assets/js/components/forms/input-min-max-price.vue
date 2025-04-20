<template>
    <div class="input-min-max-price fe-input-wrap">
        <!-- Asking Price (Number {min} to {max}) -->
        <input-textual
        v-if="!useMinSelect"
        type="price"
        :name="minName"
        :value="min"
        :placeholder="minPlaceholder"
        :label="label"
        :input-step="0.01"
        :input-min="0"
        :wrap-class="minClass"
        @change="minChanged"
        :disable-default-old="disableDefaultOld"></input-textual>

        <input-select
        v-else
        :options="minSelectOptions"
        :name="minName"
        :value="min"
        :placeholder="minPlaceholder"
        :wrap-class="minClass"
        @change="minChanged"
        :label="label"
        :disable-default-old="disableDefaultOld"></input-select>

        <strong class="self-end mr2 fz-18 price-range-to">to</strong>

        <input-textual
        v-if="!useMaxSelect"
        type="price"
        :name="maxName"
        :value="max"
        :placeholder="maxPlaceholder"
        :input-step="0.01"
        :input-min="0"
        :wrap-class="maxClass"
        @change="maxChanged"
        :disable-default-old="disableDefaultOld"></input-textual>

        <input-select
        v-else
        :options="maxSelectOptions"
        :name="maxName"
        :value="max"
        :placeholder="maxPlaceholder"
        :wrap-class="maxClass"
        @change="maxChanged"
        :disable-default-old="disableDefaultOld"></input-select>
    </div>
</template>

<script>
let _foreach = require("lodash.foreach");
let _map = require("lodash.map");
let _filter = require("lodash.filter");
let _includes = require("lodash.includes");

module.exports = {
    name: "input-min-max-price",

    mixins: [require("./../../mixins/utilities.js")],

    props: {
        name: {
            type: String,
            required: true
        },

        minValue: {
            default: ""
        },

        maxValue: {
            default: ""
        },

        label: {
            type: String,
            default: ""
        },

        minSelectValues: {
            type: Array,
            default() {
                return [];
            }
        },

        maxSelectValues: {
            type: Array,
            default() {
                return [];
            }
        },

        formId: {
            type: String,
            default: ""
        },

        disableDefaultOld: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            minClass: "mb0 mr2 nowrap",
            maxClass: "mb0 hide-labels self-end",
            minSelectOptions: this.convertValues(this.minSelectValues),
            maxSelectOptions: this.convertValues(this.maxSelectValues)
        };
    },

    computed: {
        min: {
            get() {
                return this.minValue;
            },

            set() {}
        },

        max: {
            get() {
                return this.maxValue;
            },

            set() {}
        },

        useMinSelect() {
            return this.minSelectValues.length > 0;
        },

        useMaxSelect() {
            return this.maxSelectValues.length > 0;
        },

        maxPlaceholder() {
            if (this.maxSelectValues.length > 0) {
                return "No Maximum";
            }

            return "No Max";
        },

        minPlaceholder() {
            if (this.minSelectValues.length > 0) {
                return "No Minimum";
            }

            return "No Min";
        },

        maxName() {
            return `${this.name}_max`;
        },

        minName() {
            return `${this.name}_min`;
        }
    },

    methods: {
        convertValues(values) {
            return _map(values, value => {
                let amount = parseFloat(value);
                if (isNaN(amount)) {
                    return value;
                }

                return {
                    value: amount,
                    label: this.formatPrice(value)
                };
            });
        },

        minChanged(input) {
            this.min = input.value;
            this.$emit("min-change", input);

            // Filter the maximum select if needed.
            if (this.useMaxSelect) {
                this.filterSelectOptions(input, false);
            }
        },

        filterSelectOptions(input, filterMin) {
            // Get the correct set of values.
            let values = this.maxSelectValues;
            if (filterMin) {
                values = this.minSelectValues;
            }

            if (input.value === "") {
                values = filterMin
                    ? this.minSelectValues
                    : this.maxSelectValues;
            } else {
                values = _filter(values, value => {
                    // Skip over non-numbers
                    if (isNaN(value)) {
                        return true;
                    }

                    value = parseFloat(value, 10);
                    let inputvalue = parseFloat(input.value);

                    // When filtering the minimum value
                    // check for values less than the max.
                    if (filterMin) {
                        return value < inputvalue;
                    }

                    // When filtering the maximum value
                    // check for values more than the min.
                    return value > inputvalue;
                });
            }

            // Set the value to empty if it no longe exists.
            if (filterMin && !_includes(values, parseInt(this.min, 10))) {
                this.min = "";
            }

            // Fill the min select options
            if (filterMin) {
                this.minSelectOptions = this.convertValues(values);
                return;
            }

            // Fill the max select options
            this.maxSelectOptions = this.convertValues(values);
        },

        maxChanged(input) {
            this.max = input.value;
            this.$emit("max-change", input);

            // Filter the minimum select if needed.
            if (this.useMinSelect) {
                this.filterSelectOptions(input, true);
            }
        },

        setMaxInputWidth() {
            let selector = this.useMinSelect ? "select" : "input";
            let inputs = this.$el.querySelectorAll(selector);
            let width = inputs[0].offsetWidth;

            _foreach(inputs, input => (input.style.minWidth = `${width}px`));
        },

        reset() {
            this.minSelectOptions = this.convertValues(this.minSelectValues);
            this.maxSelectOptions = this.convertValues(this.maxSelectValues);
        }
    },

    mounted() {
        this.originalValue = { min: this.minValue, max: this.maxValue };
        this.inputValue = { min: this.minValue, max: this.maxValue };

        let form = document.getElementById(this.formId);
        if (form) {
            document.addEventListener("reset", this.reset);
        }
    }
};
</script>
