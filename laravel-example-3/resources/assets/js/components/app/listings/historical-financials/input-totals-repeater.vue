<template>
    <div class="-ml1-m -mr1-m -mb2-m repeater-input">
        <app-historical-financial-input-total-labels
        :label="label"
        :data-most-recent-year="mostRecentYear"
        :data-most-recent-quarter="mostRecentQuarter"
        :tooltip="tooltip"
        ></app-historical-financial-input-total-labels>

        <input-repeater
        :values="rows"
        :default-value="defaultValue"
        :add-new-label="addNewLabel"
        :right-align-add-new-label="true"
        @added="emitUpdated"
        @removed="emitUpdated">
            <template scope="props">
                <app-historical-financial-input-yearly-totals
                :data-most-recent-year="mostRecentYear"
                :data-most-recent-quarter="mostRecentQuarter"
                :name="`${name}[${props.repeatIndex}]`"
                :name-placeholder="namePlaceholder"
                :values="props.input"
                :data-enable-negative="!!props.input.enableNegative"
                :year-established="yearEstablished"></app-historical-financial-input-yearly-totals>
            </template>
        </input-repeater>
    </div>
</template>

<script>
let moment = require("moment");
let defaultValue = {
    customName: "",
    year1: "",
    year2: "",
    year3: "",
    year4: "",
    enableNegative: false
};

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

        tooltip: {
            type: String,
            default: ""
        },

        namePlaceholder: {
            type: String,
            default: ""
        },

        name: {
            type: String,
            required: true
        },

        addNewLabel: {
            type: String,
            default: "Add new"
        },

        rows: {
            type: Array,
            default() {
                return [defaultValue];
            }
        }
    },

    data() {
        return {
            defaultValue: defaultValue
        };
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

    methods: {
        emitUpdated() {
            this.$emit("updated", this.name);
            window.Bus.$emit("input-totals-repeater:updated", this.name);
        }
    }
};
</script>
