<template>
    <app-form-accordion id="historical_financial_data_period_selection" header-title="Historical Financial Data Period Selection">
        <template slot="content">
            <div class="flex">
                <div class="flex-6 pr2">
                    <input-select
                    name="hf_most_recent_year"
                    label="Most Recent Full Year Available"
                    v-model="year"
                    :options="allYearOptions"
                    validation="required"
                    placeholder="Select a year"></input-select>

                    <input-select
                    name="hf_most_recent_quarter"
                    label="Most Recent Year to Date Quarter Available"
                    v-model="quarter"
                    :options="quarterOptions"
                    placeholder="Select a quarter"></input-select>
                </div>

                <div class="flex-6 pl2 fc-color4">
                    The historical financials tool allows users to enter data for up to the last 3 full years, plus the current year. However, we understand that different businesses will have different data availability. By providing the information below, you will give potential buyers an idea of what the most recently available data is. If you update with more recent data, be sure to update your selections.
                </div>
            </div>
        </template>
    </app-form-accordion>
</template>

<script>
let _filter = require("lodash.filter");
let _values = require("lodash.values");

module.exports = {
    name: "app-historical-financial-period-selection",

    props: {
        yearValue: {
            type: [String, Number],
            default: ""
        },

        yearOptions: {
            type: [Object, Array]
        },

        quarterValue: {
            type: [String, Number],
            default: ""
        }
    },

    data() {
        return {
            quarter: this.quarterValue ? this.quarterValue : "",
            year: this.yearValue ? this.yearValue : "",
            quarterOptions: [
                { label: "None Available", value: 0 },
                { label: "1st Quarter", value: 1 },
                { label: "2nd Quarter", value: 2 },
                { label: "3rd Quarter", value: 3 }
            ]
        };
    },

    computed: {
        allYearOptions() {
            let yearOptions = _values(this.yearOptions);
            let year = parseInt(this.year, 10);

            if (isNaN(year)) {
                return yearOptions;
            }

            let currentOptions = _filter(yearOptions, option => {
                return parseInt(option.value, 10) === year;
            });

            if (currentOptions.length === 0 && !!this.year) {
                yearOptions.push({
                    label: this.year,
                    value: this.year
                });
            }

            return yearOptions;
        }
    },

    methods: {
        staleDataAlert() {
            // window.flashAlert("Test", { type: "error" });
        },

        emitYearChanged() {
            this.$emit("year-changed", this.year);
            window.Bus.$emit("hf-period-selection:year-changed", this.year);
        },

        emitQuarterChanged() {
            this.$emit("quarter-changed", this.quarter);
            window.Bus.$emit(
                "hf-period-selection:quarter-changed",
                this.quarter
            );
        }
    },

    watch: {
        year() {
            this.emitYearChanged();
        },
        quarter() {
            this.emitQuarterChanged();
        }
    },

    mounted() {
        window.Vue.nextTick(() => {
            this.emitYearChanged();
            this.emitQuarterChanged();
        });
    }
};
</script>
