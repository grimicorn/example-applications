<template>
    <div
    class="fe-input-type-checkbox-wrap"
    :class="inputWrapClass">
        <div :class="{active: isActive}">
            <input
            type="checkbox"
            :name="name"
            :id="inputId"
            class="fe-input-type-checkbox"
            value="on"
            :class="inputClasses"
            :disabled="inputDisabled"
            :readonly="inputReadonly"
            v-validate="validationRules"
            v-model="inputValue">

             <input type="hidden" v-if="!inputValue && !disableOff" value="off" :name="name">

            <label
            :for="inputId"
            v-if="inputLabel"
            :class="labelClass">
                <span
                class="fe-input-label-inner"
                v-html="inputLabel"></span>
            </label>
        </div>

        <input-error-message
        :message="errorMessage"></input-error-message>
    </div>
</template>

<script>
let _filter = require("lodash.filter");
let inputMixins = require("./../../mixins/input-mixin.js");

module.exports = {
    mixins: [inputMixins],

    props: {
        dataDisableOff: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            disableOff: this.dataDisableOff
        };
    },

    computed: {
        isActive: {
            get() {
                return this.inputValue || this.inputValue === "on";
            },

            set() {}
        },

        inputLabel() {
            if (!this.label) {
                return "";
            }

            return this.label && this.isRequired
                ? `${this.label}*:`
                : `${this.label}`;
        }
    },

    watch: {
        inputValue(value) {
            this.isActive = !!value;
        }
    }
};
</script>
