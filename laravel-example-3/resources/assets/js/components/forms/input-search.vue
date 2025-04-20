<template>
    <form
    @submit="submit($event)"
    :class="inputWrapClass"
    class="search-wrap flex">
        <input
        type="search"
        placeholder="Search"
        class="search"
        v-model="inputValue"
        :name="name"
        :id="inputId">

        <span
        class="search-clear"
        v-show="inputValue">
            <i
            class="fa fa-times-circle-o pointer"
            aria-hidden="true"
            @click="clear"></i>
        </span>

        <button
        type="submit"
        class="fa fa-search search-button">
            <span class="sr-only">Search</span>
        </button>
    </form>
</template>

<script>
let inputMixins = require('./../../mixins/input-mixin.js');

module.exports = {
    name: 'input-search',

    mixins: [inputMixins],

    props: {
        name: {
            type: String,
            default: 'search',
        },
    },

    methods: {
        submit(event = false) {
            this.$emit('submit', this.inputValue.trim());

            if (event) {
                event.preventDefault();
            }
        },

        clear() {
            this.inputValue = '';
            this.submit();
        },
    },

    watch: {
        inputValue(value) {
            if (!value) {
                this.$emit('cleared');
                window.Bus.$emit('input-search:cleared');
            }
        },
    },
};
</script>
