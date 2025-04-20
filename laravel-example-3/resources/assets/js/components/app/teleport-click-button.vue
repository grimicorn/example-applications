<template>
    <button
        class="sticky-form-button btn"
        type="button"
        @click="teleportClick"
    >{{ label }}</button>
</template>

<script>
export default {
    props: {
        dataLabel: {
            type: String,
            required: true,
        },

        dataToSelector: {
            type: String,
            required: true,
        },
    },

    data() {
        return {
            label: this.dataLabel,
            toSelector: this.dataToSelector,
        };
    },

    computed: {
        $to() {
            return document.querySelector(this.toSelector);
        },
    },

    methods: {
        teleportClick() {
            if (this.$to) {
                this.$to.click();
            }
        },
    },
};
</script>
