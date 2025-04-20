<template>
    <div
    class="fe-input-type-toggle-wrap clearfix"
    :class="inputWrapClass">
        <!-- Label - Default -->
        <input-label
        :input-id="inputId"
        :label="toggleLabel"
        :label-class="toggleLabelClass"
        @click="toggle"
        v-if="!reverse"
        :tooltip="tooltip"></input-label>

        <!-- Toggle -->
        <div
        class="fe-input-toggle-display clearfix"
        :class="{
            'is-on' : 'on' === this.radioValue,
            'is-off' : 'off' === this.radioValue,
        }"
        @click="toggle">
            <i
            class="fa fa-check fe-input-toggle-display-option is-option-on"
            aria-hidden="true">
                <span class="sr-only">On</span>
            </i>

            <i class="fa fa-times fe-input-toggle-display-option is-option-off" aria-hidden="true">
                <span class="sr-only">Off</span>
            </i>

            <span class="fe-input-toggle-display-marker"></span>
        </div>

        <!-- Label - Reverse -->
        <input-label
        :input-id="inputId"
        :label="toggleLabel"
        :label-class="labelClass"
        class="pl1"
        @click="toggle"
        v-if="reverse"
        :tooltip="tooltip"></input-label>

        <!-- Checkbox -->
        <div class="fe-input-toggle-radios">
            <input
            value="on"
            type="radio"
            :name="name"
            :id="`${id}_on`"
            v-model="radioValue">

            <input
            value="off"
            type="radio"
            :name="name"
            :id="`${id}_off`"
            v-model="radioValue">
        </div>
    </div>
</template>

<script>
let _filter = require("lodash.filter");
let inputMixins = require("./../../mixins/input-mixin.js");

module.exports = {
    name: "input-toggle",
    mixins: [inputMixins],

    props: {
        reverse: {
            type: Boolean,
            default: false
        },

        defaultValue: {
            type: String,
            default: "off"
        }
    },

    computed: {
        radioValue: {
            get() {
                return !!this.inputValue ? "on" : "off";
            },

            set() {}
        },

        toggleLabel() {
            return this.reverse ? this.label : this.inputLabel;
        },

        toggleLabelClass() {
            if (this.reverse) {
                this.labelClass.push("flex-none");
            }

            return this.labelClass;
        }
    },

    methods: {
        toggle() {
            this.inputValue = !this.inputValue;
        }
    },

    mounted() {
        if (
            this.value === true ||
            this.value === "on" ||
            parseInt(this.value, 10) === 1
        ) {
            this.radioValue = "on";
            this.inputValue = true;
        } else {
            this.radioValue = "off";
            this.inputValue = false;
        }
    }
};
</script>
