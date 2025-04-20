<template>
    <div class="-ml1-m -mr1-m -mb2-m">
        <app-historical-financial-input-total-labels
        :label="label"
        :data-most-recent-year="mostRecentYear"
        :data-most-recent-quarter="mostRecentQuarter"></app-historical-financial-input-total-labels>

        <app-historical-financial-input-yearly-totals
        v-for="(row,index) in rows"
        :name="row.name"
        :label="row.label"
        :key="index"
        :name-placeholder="row.namePlaceholder"
        :tooltip="row.tooltip"
        :values="row.values"
        :data-enable-negative="!!row.enableNegative"
        :year-established="yearEstablished"
        :data-most-recent-year="mostRecentYear"
        :data-most-recent-quarter="mostRecentQuarter"></app-historical-financial-input-yearly-totals>
    </div>
</template>

<script>
let moment = require("moment");

module.exports = {
    props: {
        dataMostRecentYear: {
            type: [Number, String],
            default() {
                return parseInt(
                    moment()
                        .subtract(1, "year")
                        .format("Y")
                );
            }
        },

        dataMostRecentQuarter: {
            type: [Number, String],
            default: 0
        },

        yearEstablished: {
            type: [String, Number],
            default: 0
        },

        label: {
            type: String,
            default: ""
        },

        rows: {
            type: Array,
            default() {
                return [];
            }
        }
    },

    data() {
        return {};
    },

    computed: {
        mostRecentYear: {
            get() {
                return this.dataMostRecentYear;
            },

            set() {}
        },

        mostRecentQuarter: {
            get() {
                return this.dataMostRecentQuarter;
            },

            set() {}
        }
    },

    methods: {}
};
</script>
