<template>
    <div class="flex total-labels">
        <!-- Label -->
        <input-label
        :label="label"
        class="width-40 pr1 pl1"
        :tooltip="tooltip"></input-label>

        <!-- Year 1 Label -->
        <input-label
        :label="year1Label"
        class="width-15 pr1 pl1 text-right"></input-label>

        <!-- Year 2 Label -->
        <input-label
        :label="year2Label"
        class="width-15 pr1 pl1 text-right"></input-label>

        <!-- Year 3 Label -->
        <input-label
        :label="year3Label"
        class="width-15 pr1 pl1 text-right"></input-label>

        <!-- Year 4 Label -->
        <input-label
        :label="year4Label"
        class="width-15 pr1 pl1 text-right"></input-label>
    </div>
</template>

<script>
let moment = require("moment");
let _map = require("lodash.map");

module.exports = {
    props: {
        label: {
            type: String,
            default: ""
        },

        tooltip: {
            type: String,
            default: ""
        },

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
        }
    },

    data() {
        return {};
    },

    computed: {
        mostRecentYear() {
            if (!this.dataMostRecentYear) {
                return parseInt(
                    moment()
                        .subtract(1, "year")
                        .format("Y")
                );
            }

            return this.dataMostRecentYear;
        },

        mostRecentQuarter() {
            return this.dataMostRecentQuarter;
        },

        year1() {
            return parseInt(
                moment(this.mostRecentYear, "YYYY")
                    .startOf("year")
                    .subtract(2, "years")
                    .format("Y"),
                10
            );
        },

        year2() {
            return parseInt(
                moment(this.mostRecentYear, "YYYY")
                    .startOf("year")
                    .subtract(1, "years")
                    .format("Y"),
                10
            );
        },

        year3() {
            return parseInt(this.mostRecentYear, 10);
        },

        year4() {
            return parseInt(
                moment(this.mostRecentYear, "YYYY")
                    .startOf("year")
                    .add(1, "years")
                    .format("Y"),
                10
            );
        },

        year1Label() {
            return this.year1.toString();
        },

        year2Label() {
            return this.year2.toString();
        },

        year3Label() {
            return this.year3.toString();
        },

        year4Label() {
            let label = this.year4.toString();

            return `${this.quarterLabel} ${label} YTD`;
        },

        quarterLabel() {
            switch (parseInt(this.mostRecentQuarter, 10)) {
                case 1:
                    return "Q1";
                    break;

                case 2:
                    return "Q2";
                    break;

                case 3:
                    return "Q3";
                    break;

                default:
                    return "";
                    break;
            }
        }
    }
};
</script>
