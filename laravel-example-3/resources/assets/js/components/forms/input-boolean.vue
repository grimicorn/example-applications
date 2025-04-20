<template>
    <div class="clearfix fe-input-boolean-wrap">
        <input-label
        :label="label"
        :input-id="inputId"></input-label>

        <input-radio
        :name="name"
        :value="assessOption"
        :validation-message="validationMessage"
        :inputs="inputs"
        :default-value="defaultValue"
        :wrap-class="booleanInputWrapClass"
        @change="changed"></input-radio>
    </div>
</template>

<script>
let inputMixins = require("./../../mixins/input-mixin.js");

module.exports = {
    name: "input-boolean",

    mixins: [inputMixins],

    props: {
        defaultValue: {
            type: String,
            default: "off"
        }
    },

    data() {
        return {};
    },

    computed: {
        inputs() {
            return [
                { label: "Yes", value: "on" },
                { label: "No", value: "off" }
            ];
        },

        assessOption() {
            if (this.value === null) {
                return "";
            }

            return this.inputValue;
        },

        booleanInputWrapClass() {
            let inputWrapClass = this.inputWrapClass;

            inputWrapClass.push("hide-labels");

            return inputWrapClass.join(" ");
        }
    },

    methods: {
        changed(input) {
            this.$emit("change", input);
        }
    }
};
</script>
