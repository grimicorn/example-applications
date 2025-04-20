<template>
    <div
    class="fe-input-datepicker-wrap"
    :class="inputWrapClass">
        <input-label
        :label="label"
        :input-id="inputId"></input-label>

        <datepicker
        :value="dateValue"
        :name="name"
        :id="inputId"
        v-model="inputValue"
        :placeholder="inputPlaceholder"
        :input-class="datepickerClass"
        :format="format"></datepicker>

        <input-error-message
        :message="errorMessage"></input-error-message>
    </div>
</template>

<script>
    let inputMixins = require('./../../mixins/input-mixin.js');
    let moment = require('moment');

    module.exports = {
        name: 'input-datepicker',

        mixins: [inputMixins],

        components: {
            'datepicker': require('vuejs-datepicker'),
        },

        props: {
            format: {
                type: String,
                default: 'yyyy-MM-dd',
            },
        },

        data() {
            return {};
        },

        computed: {
            dateValue() {
                if (!this.inputValue) {
                    return '';
                }

               return moment(this.inputValue).format();
            },

            datepickerClass() {
                let inputClasses = this.inputClasses
                inputClasses.push('fe-input-textual');
                inputClasses.push('fe-input-datepicker');

                return inputClasses.join(' ');
            },
        },

        methods: {},
    };
</script>
