<template>
    <div class="inline">
        <timezone-date
        :format="format"
        :date="startDate"></timezone-date>
        <span> - </span>
        <timezone-date
        :format="format"
        :date="endDate"></timezone-date>
    </div>
</template>

<script>
module.exports = {
    props: {
        startDate: {
            type: String,
            required: true
        },

        endDate: {
            type: String,
            required: true
        },

        format: {
            type: String,
            default: "M/D/YYYY"
        }
    },

    data() {
        return {};
    },

    computed: {},

    methods: {}
};
</script>
