<template>
    <div class="clearfix">
        <input-label
        label="Card Expiration Date*:"
        input-id="month"></input-label>

        <!-- Month -->
        <input-select
        :options="filteredMonths"
        name="month"
        data-stripe="exp-month"
        placeholder="Month"
        :allow-placeholder-select="true"
        :value="cardMonth"
        v-model="cardMonth"
        validation="required"
        wrap-class="hide-labels width-auto pull-left mr1"
        input-class="width-auto"></input-select>

        <!-- Year -->
        <input-select
        :options="years"
        name="year"
        data-stripe="exp-year"
        placeholder="Year"
        :allow-placeholder-select="true"
        :value="cardYear"
        v-model="cardYear"
        validation="required"
        wrap-class="hide-labels width-auto pull-left"
        input-class="width-auto"></input-select>
    </div>
</template>

<script>
let moment = require("moment");
let _filter = require("lodash.filter");

module.exports = {
    props: {
        month: {
            type: [String, Number],
            default: ""
        },

        year: {
            type: [String, Number],
            default: ""
        }
    },

    data() {
        return {
            months: [
                "01",
                "02",
                "03",
                "04",
                "05",
                "06",
                "07",
                "08",
                "09",
                "10",
                "11",
                "12"
            ],
            cardMonth: this.month,
            cardYear: this.year,
            filteredMonths: []
        };
    },

    computed: {
        years() {
            let years = [];

            for (let index = 0; index < 10; index++) {
                years.push(
                    moment()
                        .add(index, "years")
                        .format("YYYY")
                );
            }

            return years;
        }
    },

    methods: {
        getFilteredMonths() {
            let months = this.months;
            let cardYear = parseInt(this.cardYear, 10);
            let currentYear = parseInt(moment().format("YYYY"), 10);
            let currentMonth = parseInt(moment().format("M"), 10);

            if (currentYear !== cardYear) {
                return months;
            }

            // If the filter is going to remove the month
            // lets update the card month.
            if (parseInt(month, 10) < currentMonth) {
                this.cardMonth = "";
            }

            // Filter the months
            return _filter(months, month => {
                return parseInt(month, 10) >= currentMonth;
            });
        }
    },

    watch: {
        cardMonth(value) {
            this.$emit("month-change", value);
        },
        cardYear(value) {
            this.$emit("year-change", value);
            this.filteredMonths = this.getFilteredMonths();
        }
    },

    mounted() {
        this.filteredMonths = this.getFilteredMonths();
    }
};
</script>
