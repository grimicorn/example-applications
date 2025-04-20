<template>
    <span
    v-if="timezoneDate"
    v-text="timezoneDate"></span>
</template>

<script>
let moment = require("moment-timezone");
let jstz = require("jstz");

module.exports = {
    props: {
        date: {
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

    computed: {
        timezoneDate() {
            let date = moment(this.date).format("Y-MM-DD");
            let time = moment(this.date).format("HH:mm:ss");
            let offset = jstz.determine().name();

            return moment(`${date}T${time}Z`)
                .tz(offset)
                .format(this.format);
        }
    },

    methods: {}
};
</script>
