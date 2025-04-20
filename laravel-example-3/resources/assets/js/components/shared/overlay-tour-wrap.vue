<template>
    <div class="overlay-tour-wrap">
        <slot></slot>

        <overlay-tour-final-step></overlay-tour-final-step>
    </div>
</template>

<script>
export default {
    props: {
        dataUrl: {
            type: String,
            default: '',
        },

        dataEnabled: {
            type: Boolean,
            default: false,
        },
    },

    data() {
        return {
            enabled: this.dataUrl && this.dataEnabled,
            url: this.dataUrl,
        };
    },

    computed: {},

    methods: {
        removeTourParam() {
            let href = window.location.href
                .replace(/tour[=]?[a-z0-9A-Z]?/g, '')
                .replace('?&', '?')
                .replace('&&', '&')
                .replace(/^\?+|\?+$/g, '');
            window.history.replaceState({}, document.title, href);
        },

        getSteps() {
            if (!this.enabled) {
                return;
            }

            window.axios
                .get(this.url)
                .then(({ data: steps }) => {
                    if (steps) {
                        this.start(steps);
                    }
                })
                .catch(error => {});
        },

        start(steps) {
            if (typeof window.introJs !== 'function') {
                return;
            }
            let overlayTour = introJs()
                .setOptions({
                    showStepNumbers: false,
                    steps: steps,
                })
                .start();
            window.Bus.$emit('introjs-started', overlayTour);
        },
    },

    mounted() {
        this.getSteps();
        this.removeTourParam();
    },
};
</script>
