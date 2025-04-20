<template>
    <timezone-date
    :format="format"
    :date="date"></timezone-date>
</template>

<script>
    module.exports = {
        props: {
            date: {
                type: String,
                required: true,
            },

            format: {
                type: String,
                default: 'M/D/YYYY h:mm a',
            }
        },

        data() {
            return {};
        },

        computed: {},

        methods: {},
    };
</script>
