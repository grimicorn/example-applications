<template>
    <!--
        Inputs have instance access to their parent form
        this way we can piggy back on that since we
        technically do not need an element
    -->
    <input type="hidden">
</template>

<script>
    module.exports = {
        props: {},

        data() {
            return {};
        },

        computed: {},

        methods: {
            submit() {
                window.Vue.nextTick(() => {
                    this.$el.form.submit();
                });
            },
        },

        mounted() {
            window.Vue.nextTick(() => {
                window.Bus.$on('search-sort-inputs:change', (values) => {
                    this.submit();
                });
            });
        }
    };
</script>
