<template>
    <div
    class="fe-input-type-select-wrap"
    :class="inputWrapClass">
        <input-label
        :input-id="inputId"
        :label="inputLabel"
        :label-class="labelClass"
        :input-value="inputValue"
        :input-maxlength="inputMaxlength"></input-label>

        <select
        type="select"
        :name="name"
        :id="inputId"
        class="fe-input-type-select"
        :class="inputClasses"
        :disabled="inputDisabled"
        :readonly="inputReadonly"
        v-validate="validationRules"
        v-model="inputValue">
            <option v-text="placeholder" value="" disabled></option>
            <option
            v-for="option in inputOptions"
            :value="option.value"
            v-text="option.label"></option>
        </select>

        <input-error-message
        :message="errorMessage"></input-error-message>
    </div>
</template>

<script>
let inputMixins = require("./../../mixins/input-mixin.js");

module.exports = {
    mixins: [inputMixins],

    data() {
        return {
            inputOptions: this.convertInputsProp(this.options)
        };
    },

    props: {
        options: {
            type: Array,
            required: true
        }
    }
};
</script>
