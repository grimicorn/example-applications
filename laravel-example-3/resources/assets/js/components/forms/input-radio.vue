<template>
    <div
    class="fe-input-type-radio-wrap"
    :class="inputWrapClass">
        <input-label
        :input-id="inputId"
        :label="inputLabel"
        :label-class="labelClass"
        :input-value="inputValue"
        :input-maxlength="inputMaxlength"></input-label>

        <ul class="fe-input-radio-list list-unstyled form-list clearfix">
            <li
            v-for="(input, index) in radioInputs"
            :class="{active: inputValue === input.value}">
                <input
                type="radio"
                :name="name"
                :id="`${inputId}_${index + 1}`"
                class="fe-input-type-radio"
                :class="inputClasses"
                :placeholder="inputPlaceholder"
                :value="input.value"
                :disabled="inputDisabled"
                :readonly="inputReadonly"
                v-validate="validationRules"
                v-model="inputValue">

                <label
                :for="`${inputId}_${index + 1}`"
                v-if="input.label"
                class="clearfix"
                :class="labelClass">
                    <span
                    class="fe-input-label-inner"
                    v-text="input.label"></span>
                </label>
            </li>
        </ul>

        <input-error-message
        :message="errorMessage"></input-error-message>
    </div>
</template>

<script>
    let inputMixins = require('./../../mixins/input-mixin.js');


    module.exports = {
        mixins: [inputMixins],

        computed: {
            radioInputs() {
                return this.convertInputsProp(this.inputs);
            }
        },

        props: {
            inputs: {
                type: Array,
                required: true,
            },

            defaultValue: {
                type: String,
                default: '',
            },
        },

        beforeMount() {
            let defaultValue = this.defaultValue;

            // Set default value if not already set.
            if (typeof this.radioInputs[0] !== 'undefined' && !defaultValue) {
                defaultValue = this.radioInputs[0].value;
            }

            // Set the input value to the default.
            if (!this.inputValue) {
                this.inputValue = defaultValue;
            }
        },
    };
</script>
