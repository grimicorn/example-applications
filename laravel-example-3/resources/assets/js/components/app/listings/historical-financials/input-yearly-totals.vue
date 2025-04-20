<template>
    <div class="flex mb2-m">
        <div class="width-40 flex items-center pr1 pl1">
            <!-- Custom Name -->
            <input-textual
            v-if="!label"
            :name="customNameInputName"
            :value="customName"
            :placeholder="namePlaceholder"
            :wrap-class="customNameInputWrapClass"
            :input-maxlength="150"
            @change="({value}) => updateValue('customName', value)"></input-textual>

            <!-- Label -->
            <input-label
            :input-id="inputId"
            :label="label"
            :input-value="inputValue"
            :tooltip="tooltip"
            v-if="label || tooltip"
            :label-class="['mb0']"></input-label>
        </div>

        <!-- Year 1 Input -->
        <input-textual
        type="price"
        :name="year1InputName"
        :value="year1"
        placeholder="Amount"
        wrap-class="hide-labels mb0 width-15 pr1 pl1 pull-right"
        input-class="text-right"
        :data-enable-negative="dataEnableNegative"
        @change="updateYear1"
        :input-disabled="year1Disabled"></input-textual>

        <!-- Year 2 Input -->
        <input-textual
        type="price"
        :name="year2InputName"
        :value="year2"
        placeholder="Amount"
        wrap-class="hide-labels mb0 width-15 pr1 pl1 pull-right"
        input-class="text-right"
        :data-enable-negative="dataEnableNegative"
        @change="updateYear2"
        :input-disabled="year2Disabled"></input-textual>

        <!-- Year 3 Input -->
        <input-textual
        type="price"
        :name="year3InputName"
        :value="year3"
        placeholder="Amount"
        wrap-class="hide-labels mb0 width-15 pr1 pl1 pull-right"
        input-class="text-right"
        :data-enable-negative="dataEnableNegative"
        @change="updateYear3"
        :input-disabled="year3Disabled"></input-textual>

        <!-- Year 4 Input -->
        <input-textual
        type="price"
        :name="year4InputName"
        :value="year4"
        placeholder="Amount"
        wrap-class="hide-labels mb0 width-15 pr1 pl1 pull-right"
        input-class="text-right"
        :data-enable-negative="dataEnableNegative"
        @change="updateYear4"
        :input-disabled="year4Disabled"></input-textual>
    </div>
</template>

<script>
let _map = require("lodash.map");
module.exports = {
    name: "app-historical-financial-input-yearly-totals",

    props: {
        name: {
            type: String,
            required: true
        },

        id: {
            type: String,
            default: ""
        },

        values: {
            type: Object,
            default() {
                return {
                    customName: "",
                    year1: "",
                    year2: "",
                    year3: "",
                    year4: "",
                    all: {}
                };
            }
        },

        label: {
            type: String,
            default: ""
        },

        tooltip: {
            type: String,
            default: ""
        },

        dataEnableNegative: {
            type: Boolean,
            default: false
        },

        namePlaceholder: {
            type: String,
            default: ""
        },

        yearEstablished: {
            type: [String, Number],
            default: 0
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
        return {
            originalYear: this.dataMostRecentYear,
            originalQuarter: this.dataMostRecentQuarter,
            inputValue: this.value,
            inputId: this.id ? this.id : this.name,
            customName: this.values.customName,
            yearValues: {}
        };
    },

    computed: {
        year1() {
            if (
                this.years.year1 === undefined ||
                this.yearValues === undefined ||
                this.yearValues[this.years.year1] === undefined
            ) {
                return "";
            }

            return this.year1Disabled ? "" : this.yearValues[this.years.year1];
        },

        year2() {
            if (
                this.years.year2 === undefined ||
                this.yearValues === undefined ||
                this.yearValues[this.years.year2] === undefined
            ) {
                return "";
            }

            return this.year2Disabled ? "" : this.yearValues[this.years.year2];
        },

        year3() {
            if (
                this.years.year3 === undefined ||
                this.yearValues === undefined ||
                this.yearValues[this.years.year3] === undefined
            ) {
                return "";
            }

            return this.year3Disabled ? "" : this.yearValues[this.years.year3];
        },

        year4() {
            if (
                this.years.year4 === undefined ||
                this.yearValues === undefined ||
                this.yearValues[this.years.year4] === undefined
            ) {
                return "";
            }

            return this.year4Disabled ? "" : this.yearValues[this.years.year4];
        },

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
        },

        year1Disabled() {
            if (!this.yearEstablished || this.years.year1 === undefined) {
                return false;
            }

            return this.years.year1 < this.yearEstablished;
        },

        year2Disabled() {
            if (!this.yearEstablished || this.years.year2 === undefined) {
                return false;
            }

            return this.years.year2 < this.yearEstablished;
        },

        year3Disabled() {
            if (!this.yearEstablished || this.years.year3 === undefined) {
                return false;
            }

            return this.years.year3 < this.yearEstablished;
        },

        year4Disabled() {
            if (this.mostRecentYearInputDisabled) {
                return true;
            }

            if (!this.yearEstablished || this.years.year4 === undefined) {
                return false;
            }

            return this.years.year4 < this.yearEstablished;
        },

        mostRecentYearInputDisabled() {
            // The logic behind this is basically if a user selects
            // the current year and none available in the
            // "Historical Financial Data Period Selection" then the
            // current year amount input should be disabled.

            let mostRecentQuarter = parseInt(this.mostRecentQuarter, 10);
            // If nothing is selected then lets wait
            // until they select something
            if (isNaN(mostRecentQuarter)) {
                return false;
            }

            // None available quarter is 0
            // If it is any other quarter then we are ok.
            if (mostRecentQuarter > 0) {
                return false;
            }

            return true;
        },

        customNameInputWrapClass() {
            return ["hide-labels mb0", this.tooltip ? "mr1" : ""].join(" ");
        },

        customNameInputName() {
            return this.addInputNameWrapper("custom_name");
        },

        year1InputName() {
            return this.addInputNameWrapper("year1");
        },

        year2InputName() {
            return this.addInputNameWrapper("year2");
        },

        year3InputName() {
            return this.addInputNameWrapper("year3");
        },

        year4InputName() {
            return this.addInputNameWrapper("year4");
        },

        years() {
            if (!this.mostRecentYear) {
                return {};
            }

            let year1 = moment(this.mostRecentYear, "YYYY")
                .startOf("year")
                .subtract(2, "years")
                .format("Y");
            let year2 = moment(this.mostRecentYear, "YYYY")
                .startOf("year")
                .subtract(1, "years")
                .format("Y");
            let year3 = moment(this.mostRecentYear, "YYYY")
                .startOf("year")
                .format("Y");
            let year4 = moment(this.mostRecentYear, "YYYY")
                .startOf("year")
                .add(1, "years")
                .format("Y");

            return {
                year1: parseInt(year1, 10),
                year2: parseInt(year2, 10),
                year3: parseInt(year3, 10),
                year4: parseInt(year4, 10)
            };
        }
    },

    methods: {
        updateYear1({ value }) {
            if (!this.year1Disabled) {
                this.updateValue("year1", value);
            }
        },

        updateYear2({ value }) {
            if (!this.year2Disabled) {
                this.updateValue("year2", value);
            }
        },

        updateYear3({ value }) {
            if (!this.year3Disabled) {
                this.updateValue("year3", value);
            }
        },

        updateYear4({ value }) {
            if (!this.year4Disabled) {
                this.updateValue("year4", value);
            }
        },

        addInputNameWrapper(collection) {
            // A name can include other "array" segments.
            // So we will want to break it off at the first segment if it exists
            // and use the first item as a name along with the remaining segments appeneded to the end.
            let namePieces = this.name.split(/\[(.+)/);
            let name = namePieces[0];
            namePieces[0] = "";
            let suffix = namePieces.join("").trim();
            if (suffix.indexOf("]") !== -1) {
                suffix = `[${suffix}`;
            }

            return `${collection}[${name}]${suffix}`;
        },

        updateValue(index, value) {
            if (this.years[index] !== undefined) {
                this.yearValues[this.years[index]] = value;
            }

            this.$emit("value-updated", {
                name: this.customName,
                year1: this.year1,
                year2: this.year2,
                year3: this.year3,
                year4: this.year4
            });
        },

        setupYearValues() {
            let yearValues = {};

            return this.values.all;
        }
    },

    mounted() {
        this.yearValues = this.setupYearValues();
    }
};
</script>
