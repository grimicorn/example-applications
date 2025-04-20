<template>
    <div
    class="fe-loader fc-color4"
    :class="{
        'should-cover': cover,
        'is-large': spinnerLarge,
        'inline-block pa0 width-auto': inline
    }"
    v-if="loading">
        <i
        class="fa fa-circle-o-notch fa-spin"
        :class="colorClass"
        aria-hidden="true"></i>
    </div>
</template>

<script>
module.exports = {
    props: {
        loading: {
            type: Boolean,
            default: false
        },

        cover: {
            type: Boolean,
            default: true
        },

        spinnerLarge: {
            type: Boolean,
            default: true
        },

        colorClass: {
            type: String,
            default: "fc-color4"
        },

        inline: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {};
    },

    computed: {},

    methods: {}
};
</script>
