<template>
    <div>
        <slot v-if="contentVisible"></slot>

        <button
        type="button"
        :class="buttonClass"
        v-text="buttonLabel"
        v-if="!contentVisible"
        @click="contentVisible = true"></button>
    </div>
</template>
<script>
    module.exports = {
        name: 'marketing-button-toggle-show-hide',

        props: {
            buttonClass: {
                type: String,
                default: 'btn',
            },

            buttonLabel: {
                type: String,
                default: 'Click to Display',
            },
        },

        data() {
            return {
                contentVisible: false,
            };
        },
    };
</script>
