<template>
    <app-form-accordion
    header-title="Basic Information"
    class="listing-details-the-basics">
        <div slot="content">

            <!-- Business Page Title -->
            <input-textual
            type="text"
            name="title"
            :value="listing.title"
            placeholder="Name or brief description of the business to help buyers confirm they're looking at the correct deal"
            label="Business Page Title"
            wrap-class="input-full is-right"
            :input-maxlength="200"
            validation="required"
            tooltip="This is what the business will be called and will be visible to visitors to the page.
            While you may include the business’ name here if you’d like, do not include any information that you
            wouldn't want public."></input-textual>

            <!-- Business Name -->
            <input-textual
            type="text"
            name="business_name"
            :value="listing.business_name"
            placeholder="Business's Legal Name"
            label="Business Name"
            wrap-class="input-half is-left"
            validation="required"
            tooltip="Enter your business’s legal name here. We require this to combat abuse. It is not displayed to the public."></input-textual>

            <!-- Asking Price -->
            <input-textual
            type="price"
            name="asking_price"
            :value="listing.asking_price"
            placeholder="$ Amount"
            label="Asking Price"
            wrap-class="input-half is-right"></input-textual>

            <!-- Business Category -->
            <input-select
            label="Business Category"
            name="business_category_id"
            :value="listing.business_category_id"
            :options="businessParentCategories"
            placeholder="Select a Category"
            wrap-class="input-half is-left"
            validation="required"
            @change="updateBusinessChildCategories"></input-select>

            <!-- Business Sub-Category -->
            <input-select
            label="Business Sub-Category"
            name="business_sub_category_id"
            :value="listing.business_sub_category_id"
            :options="businessChildCategories"
            :placeholder="businessChildCategoryPlaceholder"
            wrap-class="input-half is-right"></input-select>

            <!-- Name Available to Public -->
            <input-toggle
            name="name_visible"
            :value="listing.name_visible"
            label="Name Visible to Public"
            tooltip="If selected, the business name will appear on your detailed listing page and will be visible to all users of the site, including unregistered visitors."
            wrap-class="input-half is-left"></input-toggle>

            <!-- Display Sold By -->
            <input-toggle
            name="display_listed_by"
            :value="listing.display_listed_by"
            label="Sold By Visible to Public"
            tooltip="Toggling to visible adds a 'Sold By' section (which includes your basic profile information) to the detailed listing page."
            wrap-class="input-half is-right"></input-toggle>

            <!-- Business Description -->
            <input-textual
            type="textarea"
            name="business_description"
            :value="listing.business_description"
            placeholder="Detailed description of the business, market environment, and outlook."
            label="Business Overview"
            wrap-class="clear"
            :input-maxlength="1000"></input-textual>

            <!-- Address 1 -->
            <input-textual
                    type="text"
                    name="address_1"
                    :value="listing.address_1"
                    placeholder="Address 1"
                    label="Address"
                    wrap-class="input-half is-left"></input-textual>

            <!-- Address 2 -->
            <input-textual
                    type="text"
                    name="address_2"
                    :value="listing.address_2"
                    placeholder="Address 2"
                    label="Address 2"
                    wrap-class="input-half is-right"></input-textual>

            <!-- Address Visible toggle, hidden in an enabled state -->
            <input-toggle
                    name="address_visible"
                    display="none"
                    :value="true"
                    label="Address Visible to Public"
                    wrap-class="hide-desktop"></input-toggle>

            <!-- City -->
            <input-textual
            type="text"
            name="city"
            :value="listing.city"
            placeholder="City"
            label="City"
            wrap-class="input-half is-left"></input-textual>

            <!-- State -->
            <input-select
            name="state"
            :value="listing.state"
            :options="statesForSelect"
            placeholder="State"
            label="State"
            wrap-class="input-half is-right"></input-select>

            <!-- Zip Code -->
            <input-textual
            type="text"
            name="zip_code"
            :value="listing.zip_code"
            placeholder="Zip Code"
            label="Zip Code"
            wrap-class="input-half is-left"></input-textual>

        </div>
    </app-form-accordion>
</template>

<script>
let _filter = require("lodash.filter");

module.exports = {
    props: {
        dataListing: {
            type: Object,
            required: true
        },

        old: {
            type: Array
            // required: true,
        },

        dataBusinessParentCategories: {
            type: Array,
            required: true
        },

        dataBusinessChildCategories: {
            type: Array,
            required: true
        },

        formId: {
            type: String,
            default: ""
        },

        percentageComplete: {
            type: Number,
            default: 0
        }
    },

    data() {
        return {
            listing: this.dataListing,
            businessParentCategories: this.dataBusinessParentCategories,
            businessChildCategories: this.dataBusinessChildCategories,
            businessChildCategoryPlaceholder: "Business Sub-Category"
        };
    },

    computed: {
        statesForSelect() {
            return (typeof window.Spark.statesForSelect === 'undefined') ? [] : window.Spark.statesForSelect;
        },
    },

    methods: {
        updateBusinessChildCategoryLabel(input) {
            let defaultValue = "Business Sub-Category";

            if (input.value === "") {
                this.businessChildCategoryPlaceholder = defaultValue;
                return;
            }

            let categories = this.dataBusinessParentCategories;
            let selectedId = parseInt(input.value, 10);
            let parentCat = _filter(categories, category => {
                return parseInt(category.value, 10) === selectedId;
            })[0];

            if (typeof parentCat !== "undefined") {
                this.businessChildCategoryPlaceholder = `${
                    parentCat.label
                } Sub-Category`;

                return;
            }

            this.businessChildCategoryPlaceholder = defaultValue;
        },

        updateBusinessChildCategories(input) {
            let categories = this.dataBusinessChildCategories;
            categories = _filter(categories, category => {
                let parentId = parseInt(category.parentId, 10);
                let selectedId = parseInt(input.value, 10);

                return parentId === selectedId;
            });

            this.businessChildCategories = categories;

            // Set the label
            this.updateBusinessChildCategoryLabel(input);
        }
    }
};
</script>
