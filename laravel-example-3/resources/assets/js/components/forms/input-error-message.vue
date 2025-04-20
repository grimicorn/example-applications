<template>
    <div
    class="fe-input-error-message"
    v-show="message">
        {{ message }}
        <i
        v-if="dataAllowDismiss"
        class="fa fa-times cursor-pointer"
        aria-hidden="true"
        @click="dismiss"></i>
    </div>
</template>

<script>
module.exports = {
    props: {
        message: {
            type: String,
            required: true
        },

        dataAllowDismiss: {
            type: Boolean,
            default: false
        }
    },

    methods: {
        dismiss() {
            this.$emit("dismissed");
        },

        striptags: require("striptags")
    }
};
</script>
