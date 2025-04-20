<template>
    <div
    class="fe-input-type-select-wrap"
    :class="inputWrapClass">
        <input-label
        v-if="label"
        :input-id="inputId"
        :label="inputLabel"
        :label-class="labelClass"
        :input-value="inputValue"
        :input-maxlength="inputMaxlength"
        :tooltip="tooltip"></input-label>

        <select
        type="select"
        :name="multiple ? `${name}_select[]` : `${name}_select`"
        :id="inputId"
        class="fe-input-type-select"
        :class="inputClasses"
        :disabled="inputDisabled"
        :readonly="inputReadonly"
        v-validate="validationRules"
        :data-stripe="dataStripe"
        v-model="inputValue"
        :multiple="multiple">
            <option
            v-text="placeholder"
            v-if="placeholder"
            value=""
            :disabled="!allowPlaceholderSelect"
            class="select-placeholder"></option>

            <option
            v-for="option in inputOptions"
            :key="option.value"
            :value="option.value"
            v-text="option.label"></option>
        </select>

        <input
        v-for="fauxValue in fauxValues"
        :key="fauxValue"
        type="hidden"
        :name="multiple ? `${name}[]` : name"
        :id="inputId"
        :disabled="inputDisabled"
        :readonly="inputReadonly"
        :data-stripe="dataStripe"
        :value="fauxValue">

        <input-error-message
        :message="errorMessage"></input-error-message>

        <!-- Select Other -->
        <input-textual
        v-if="displayOther"
        :name="otherMerged.name"
        :value="otherMerged.value"
        :placeholder="otherMerged.placeholder"
        :validation-message="otherMerged.validationMessage"
        :label="otherMerged.label"></input-textual>
    </div>
</template>

<script>
let inputMixins = require('./../../mixins/input-mixin.js');
let utilityMixins = require('./../../mixins/utilities.js');
let _merge = require('lodash.merge');

module.exports = {
    mixins: [inputMixins, utilityMixins],

    props: {
        dataStripe: {
            type: String,
            default: '',
        },

        tooltip: {
            type: String,
            default: '',
        },

        allowPlaceholderSelect: {
            type: Boolean,
            default: true,
        },

        options: {
            required: true,
        },

        other: {
            type: Object,
        },

        multiple: {
            type: Boolean,
            default: false,
        },
    },

    data() {
        return {
            inputOptions: this.convertInputsProp(this.options),
            displayOther: false,
        };
    },

    computed: {
        otherMerged() {
            let defaultOther = {
                optionValue: '',
                name: '',
                value: '',
                validationMessage: '',
                label: '',
                placeholder: '',
            };

            if (typeof this.other === 'undefined') {
                return defaultOther;
            }

            return _merge(defaultOther, this.other);
        },

        fauxValues() {
            return this.multiple
                ? Object.values(this.inputValue)
                : [this.inputValue];
        },
    },

    methods: {
        fixEdgeBrowser() {
            if (!this.isEdgeBrowser()) {
                return;
            }

            // For some reason the selects do not update the display value...
            let inputValue = this.inputValue;
            this.inputValue = '';
            window.Vue.nextTick(() =>
                setTimeout(() => (this.inputValue = inputValue), 300)
            );
        },
    },

    watch: {
        value(value) {
            this.inputValue = value;
        },

        inputValue(newValue) {
            let optionValue = this.otherMerged.optionValue;
            this.displayOther = optionValue !== '' && optionValue === newValue;
        },

        displayOther(newValue) {
            if (!newValue) {
                this.otherMerged.value = '';
            }
        },

        options(options) {
            this.inputOptions = this.convertInputsProp(options);
        },
    },

    created() {
        if (this.multiple) {
            this.inputValue =
                typeof this.inputValue === 'string'
                    ? []
                    : Object.values(this.inputValue);
        }
    },

    mounted() {
        this.fixEdgeBrowser();
    },
};
</script>
