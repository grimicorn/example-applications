<template>
    <div
    class="fe-input-type-range-wrap"
    :class="inputWrapClass">
        <input-label
        :input-id="inputId"
        :label="inputLabel"
        :label-class="labelClass"></input-label>

        <vue-slider
        ref="slider"
        v-model="inputValue"
        :min="minRange"
        :max="maxRange"
        :tooltip="false"></vue-slider>

        <input
        type="hidden"
        :name="name"
        v-model="inputValue"
        v-validate="validationRules">

        <div class="input-range-status-message">
            <slot
            name="status-message"
            :value="inputValue"></slot>
        </div>

        <input-error-message
        :message="errorMessage"></input-error-message>
    </div>
</template>

<script>
    let inputMixins = require('./../../mixins/input-mixin.js');

    module.exports = {
        name: 'input-range',

        mixins: [inputMixins],

        components: {
            'vue-slider': require('vue-slider-component'),
        },

        data() {
             return {};
        },

        props: {
            minRange: {
                type: Number,
                default: 0,
            },

            maxRange: {
                type: Number,
                default: 100,
            },
        },

        computed: {},

        methods: {},
    };
</script>
