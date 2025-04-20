<template>
<div class="feature-switchers-wrap">
    <feature-switcher-large
    :features="features"
    v-if="useLarge"></feature-switcher-large>

    <feature-switcher-small
    :features="features"
    v-else></feature-switcher-small>
</div>
</template>

<script>
    module.exports = {
        name: 'marketing-feature-switcher',

        mixins: [
            require('../../mixins/utilities.js'),
        ],

        data() {
            let useLargeWidth = 800;

            return {
                useLarge: this.windowIsMinWidth(useLargeWidth),
                useLargeWidth: useLargeWidth,
            };
        },

        components: {
            'feature-switcher-large': require('./feature-switcher/feature-switcher-large.vue'),
            'feature-switcher-small': require('./feature-switcher/feature-switcher-small.vue'),
        },

        props: {
            features: {
                type: Array,
                required: true,
            },
        },

        mounted() {
            // Decide which size to use based on the window size when resizing.
            window.addEventListener('resize', () => {
                this.useLarge = this.windowIsMinWidth(this.useLargeWidth);
            }, true);
        },
    };
</script>
