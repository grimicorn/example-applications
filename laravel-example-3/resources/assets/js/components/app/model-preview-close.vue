<template>
    <button
    type="button"
    @click="close">Close Preview</button>
</template>

<script>
module.exports = {
    props: {},

    data() {
        return {};
    },

    computed: {},

    methods: {
        close() {
            window.close();
        }
    }
};
</script>
