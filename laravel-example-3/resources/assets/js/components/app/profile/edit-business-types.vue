<template>
    <div class="profile-edit-business-types clearfix">
        <!-- Business Type(s) -->
        <input-business-category-select
        :name="inputId"
        label="Business Type(s)"
        :value="values"
        wrap-class="input-half is-left"
        :validation-message="validationMessage"
        @select-display-change="handleSelectDisplayChange"></input-business-category-select>

        <!-- Business Type(s) Selected: -->
        <div class="fe-input-wrap input-half is-right">
            <input-label
            :input-id="inputId"
            label="Business Type(s) Selected:"></input-label>

            <div
            class="business-type-listings list-unstyled"
            v-text="listItems.join(', ')"></div>
        </div>
    </div>
</template>

<script>
let _foreach = require("lodash.foreach");

module.exports = {
    data() {
        return {
            selectedValues: {},
            inputId: "desiredPurchaseCriteria[business_categories][]"
        };
    },

    computed: {
        listItems() {
            let listItems = [];

            _foreach(this.selectedValues, function(selectedValue) {
                listItems.push(selectedValue.label);
            });

            return listItems;
        },

        listings() {
            return this.values;
        }
    },

    props: {
        values: {
            type: Array,
            default() {
                return [];
            }
        },

        validationMessage: {
            type: String,
            default: ""
        }
    },

    methods: {
        handleSelectDisplayChange(selectedValues) {
            this.selectedValues = selectedValues;
        }
    }
};
</script>
