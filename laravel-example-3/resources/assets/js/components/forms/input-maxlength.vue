<template>
    <div
    :class="{
        'input-maxlength': true,
        'has-error': this.isError,
    }"
    v-text="`${inputValue.length}/${maxlength}`"
    v-if="maxlength > 0"
    v-show="maxlength < 1000">
    </div>
</template>

<script>
    module.exports = {
        data() {
            return {
                isError: (this.currentValue().length > this.maxlength),
                inputValue: this.currentValue(),
            };
        },

        watch: {
            value(value) {
                this.inputValue = this.currentValue();
                this.isError = (this.inputValue.length > this.maxlength);
            },
        },

        methods: {
            currentValue() {
                let value = this.value;
                let isNull = value === null ||value === 'null';
                let isUndefined = typeof value === 'undefined';

                return (isNull ||isUndefined) ? '' : value;
            }
        },

        props: {
            value: {
                default: '',
            },
            maxlength: {
                type: Number,
                default: -1,
            },
        },
    };
</script>
