<template>
    <div :class="inputTextWrapClass">
        <!-- Label -->
        <input-label
        :input-id="inputId"
        :label="inputLabel"
        :hideColon="hideColon"
        :label-class="labelClass"
        :input-value="inputValue"
        :input-maxlength="inputMaxlength"
        :tooltip="tooltip"></input-label>

        <!-- Textarea -->
        <div v-if="isTextarea">
            <textarea
            :name="`${name}_textarea`"
            :id="inputId"
            :class="inputClasses"
            :placeholder="inputTextPlaceholder"
            :disabled="inputDisabled"
            :readonly="inputReadonly"
            v-validate="validationRules"
            :maxlength="inputMaxlength"
            v-model="textValue"
            @input="updateInputValue"
            :rows="inputRows"></textarea>

            <input type="hidden" :name="name" :value="textValue">
        </div>

        <!-- Regular masked textual input -->
        <input
        v-else-if="shouldMask"
        type="text"
        :name="name"
        :id="inputId"
        :class="inputTextClass"
        :placeholder="inputTextPlaceholder"
        :disabled="inputDisabled"
        :readonly="inputReadonly"
        :max="max"
        :min="min"
        :step="step"
        :input-maxlength="inputMaxlength"
        v-validate="inputTextValidationRules"
        v-model="textValue"
        @input="updateInputValue"
        v-mask="mask"
        :data-stripe="dataStripe">

        <!-- Price masked textual input -->
        <input
        v-else-if="isPrice"
        type="text"
        :name="name"
        :id="inputId"
        :class="inputTextClass"
        :placeholder="inputTextPlaceholder"
        :disabled="inputDisabled"
        :readonly="inputReadonly"
        :max="max"
        :min="min"
        :step="step"
        v-model="textValue"
        v-validate="inputTextValidationRules"
        :maxlength="priceMaxLength"
        @input="priceChange($event)"
        :data-stripe="dataStripe">


        <!-- Unmasked textual input -->
        <input
        v-else
        type="text"
        :name="name"
        :id="inputId"
        :class="inputTextClass"
        :placeholder="inputTextPlaceholder"
        :disabled="inputDisabled"
        :readonly="inputReadonly"
        :max="max"
        :min="min"
        :step="step"
        v-validate="inputTextValidationRules"
        v-model="textValue"
        @input="updateInputValue"
        :data-stripe="dataStripe">

        <!-- Unmasked value to save -->
        <input
        type="hidden"
        v-if="shouldMask || isPrice"
        :value="submissionValue"
        :name="name">

        <input-error-message
        :message="errorMessage"></input-error-message>
    </div>
</template>

<script>
let inputMixins = require("./../../mixins/input-mixin.js");
let _foreach = require("lodash.foreach");

module.exports = {
    name: "input-textual",

    directives: {
        mask: require("vue-the-mask").mask
    },

    mixins: [inputMixins],

    props: {
        inputStep: {
            type: Number,
            default: 1
        },

        inputMax: {
            type: Number
        },

        inputMin: {
            type: Number,
            default: 0
        },

        dataStripe: {
            type: String,
            default: ""
        },

        tooltip: {
            type: String,
            default: ""
        },

        hideColon: {
            type: Boolean,
            default: false
        },

        dataEnableNegative: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            textValue: "",
            step: this.inputStep,
            min: this.inputMin,
            max: this.inputMax,
            priceMaxLength: 13,
            isPrice: this.type === "price",
            enableNegative: this.dataEnableNegative
        };
    },

    computed: {
        submissionValue() {
            if (!this.isPrice) {
                return this.inputValue;
            }

            return this.inputValue === "-" ? "" : this.inputValue;
        },

        inputTextPlaceholder() {
            return this.shouldMask ? this.mask : this.inputPlaceholder;
        },

        shouldMask() {
            return this.mask !== "";
        },

        mask() {
            switch (this.type) {
                case "phone":
                    return "(###) ###-####";
                    break;
                default:
                    return "";
            }
        },

        inputTextWrapClass() {
            let wrapClass = this.inputWrapClass;

            wrapClass.push(`fe-input-type-${this.type}-wrap`);
            wrapClass.push("fe-input-textual-wrap");

            return wrapClass;
        },

        inputTextClass() {
            let inputClasses = this.inputClasses;

            inputClasses.push(`fe-input-type-${this.type}`);
            inputClasses.push("fe-input-textual");

            return inputClasses;
        },

        inputTextValidationRules() {
            return this.validationRules;
        },

        isTextarea() {
            return this.type == "textarea";
        }
    },

    methods: {
        priceChange(event) {
            this.inputValue = this.unmask(event.target.value);
            window.Vue.nextTick(() => {
                this.textValue = this.formatPrice(this.inputValue);
            });
        },

        formatPrice(value) {
            value = this.unmask(value);
            if (value === "") {
                return "";
            }

            let number = parseInt(value, 10);
            if (isNaN(number)) {
                return value;
            }

            return new Intl.NumberFormat().format(number);
        },

        unmask(value) {
            value = value === null ? "" : value.toString();

            switch (this.type) {
                case "price":
                    value = value.toString().trim();
                    value = value.replace(/\..*/g, "");
                    isNegative = value.indexOf("-") === 0;
                    value = value.replace(/[^0-9]/g, "");

                    if (this.enableNegative) {
                        value = isNegative ? `-${value}` : value;
                    }

                    return value;
                    break;
                case "phone":
                    return value.replace(/[^\d]/g, "");
                    break;
                default:
                    return value;
            }
        },

        setInputType() {
            if (this.type === "url") {
                return;
            }

            let inputs = this.$el.querySelectorAll('input[type="text"]');
            _foreach(inputs, input => {
                input.setAttribute("type", this.type);
            });
        },

        updateInputValue() {
            this.inputValue = this.unmask(this.textValue);
        },

        formatTextValue(value) {
            if (this.type === "price") {
                value = this.formatPrice(value);
            }

            return value;
        }
    },

    watch: {
        inputValue(value) {
            this.textValue = this.formatTextValue(value);
        }
    },

    mounted() {
        this.setInputType();
        this.textValue = this.formatTextValue(this.inputValue);
    }
};
</script>
