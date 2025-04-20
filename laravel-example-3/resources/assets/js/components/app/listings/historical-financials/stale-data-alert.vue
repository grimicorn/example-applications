<template>
    <alert
    v-if="visible"
    type="error">
        <slot></slot>
    </alert>
</template>

<script>
module.exports = {
    props: {
        dataStale: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            visible: this.dataStale
        };
    },

    computed: {},

    methods: {},

    mounted() {
        window.Bus.$on("hf-period-selection:year-changed", value => {
            let year = parseInt(value, 10);
            if (isNaN(year)) {
                this.visible = false;
                return;
            }

            let currentYear = parseInt(new Date().getFullYear(), 10);
            this.visible = year <= currentYear - 2;
        });
    }
};
</script>
