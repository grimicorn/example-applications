<template>
    <span
    id="overlay_tour_diligence_unresolved_step"
    class="fc-color7 text-underline pointer"
    v-text="label"
    @click="filter"></span>
</template>

<script>
module.exports = {
    name: 'unresolved-conversation-count',

    props: {
        count: {
            type: Number,
            default: 0,
        },
    },

    data() {
        return {
            currentCount: this.count,
        };
    },

    computed: {
        label() {
            if (this.currentCount === 1) {
                return `${this.currentCount} unresolved conversation`;
            }

            return `${this.currentCount} unresolved conversations`;
        },
    },

    methods: {
        filter() {
            window.Bus.$emit('converation-filters:update:status', 0);
        },
    },

    mounted() {
        window.Bus.$on('conversation-resolved', () => {
            this.currentCount = this.currentCount - 1;
        });

        window.Bus.$on('conversation-unresolved', () => {
            this.currentCount = this.currentCount + 1;
        });
    },
};
</script>
