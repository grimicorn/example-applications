<template>
    <modal
    button-label="Advanced Search"
    title="Advanced Search"
    :button-class="buttonClass"
    class="inline"
    modal-class="modal-content-large"
    :use-show="true"
    @opened="() => open = true"
    @closed="() => open = false">
        <div class="row" v-if="open">
            <div class="col-sm-4">
                <!-- Zipcode -->
                <input-textual
                name="zip_code"
                label="Zip Code"
                :value="search.zip_code"
                placeholder="Any Zip Code"
                validation="numeric"
                :disable-default-old="true"
                @change="handleChange"></input-textual>

                <!-- Miles from Zip Code -->
                <input-range
                label="Miles from Zip Code"
                name="zip_code_radius"
                :min-range="0"
                :max-range="500"
                :value="search.zip_code_radius"
                :disable-default-old="true"
                @change="handleChange">
                    <template
                    slot="status-message"
                    scope="props">
                        Distance: {{ props.value }} Miles
                    </template>
                </input-range>

                <!-- State -->
                <input-select
                name="state"
                :value="search.state"
                :options="statesForSelect"
                label="State"
                placeholder="Any State"
                :disable-default-old="true"
                @change="handleChange"></input-select>

                <!-- City / Keyword -->
                <input-textual
                name="keyword"
                label="City / Keyword"
                :value="search.keyword"
                placeholder="Any City / Keyword"
                wrap-class="mr2 mb0"
                :disable-default-old="true"
                @change="handleChange"></input-textual>
            </div>

            <div class="col-sm-4">
                <!-- Asking Price (Min/Max) -->
                <input-min-max-price
                label="Asking Price"
                name="asking_price"
                :min-value="search.asking_price_min"
                :max-value="search.asking_price_max"
                @min-change="handleChange"
                @max-change="handleChange"
                :disable-default-old="true"></input-min-max-price>

                <!-- Business Categories -->
                <input-business-category-select
                name="business_categories[]"
                label="Business Category"
                :value="search.business_categories"
                placeholder="Business Categories"
                wrap-class="mr2"
                @select-change="businessCategoryChange"
                :disable-default-old="true"></input-business-category-select>

                <!-- Listing Updated -->
                <input-select
                name="listing_updated"
                label="Business Updated"
                :value="search.listing_updated"
                :options="listingUpdatedOptions"
                placeholder="Any Time"
                :disable-default-old="true"
                @change="handleChange"></input-select>
            </div>

            <div class="col-sm-4">
                <!-- Revenue (Min/Max) -->
                <input-min-max-price
                label="Revenue"
                name="revenue"
                :min-value="search.revenue_min"
                :max-value="search.revenue_max"
                @min-change="handleChange"
                @max-change="handleChange"
                :disable-default-old="true"></input-min-max-price>

                <!-- EBITDA (Min/Max) -->
                <input-min-max-price
                label="EBITDA"
                name="ebitda"
                :min-value="search.ebitda_min"
                :max-value="search.ebitda_max"
                @min-change="handleChange"
                @max-change="handleChange"
                :disable-default-old="true"></input-min-max-price>

                <!-- Pre-Tax Income (Min/Max) -->
                <input-min-max-price
                label="Pre-Tax Income"
                name="pre_tax_income"
                :min-value="search.pre_tax_income_min"
                :max-value="search.pre_tax_income_max"
                @min-change="handleChange"
                @max-change="handleChange"
                :disable-default-old="true"></input-min-max-price>

                <!-- Cash Flow (Min/Max) -->
                <input-min-max-price
                label="Cash Flow"
                name="cash_flow"
                :min-value="search.cash_flow_min"
                :max-value="search.cash_flow_max"
                @min-change="handleChange"
                @max-change="handleChange"
                :disable-default-old="true"></input-min-max-price>
            </div>

            <div class="text-center clear">
                <button type="submit" class="btn btn-color5 mr2" @click="submitSearch">Search</button>
                <button type="reset" class="btn btn-color3" @click="resetSearch">Reset</button>
            </div>
        </div>
    </modal>
</template>

<script>
let _map = require("lodash.map");
let _filter = require("lodash.filter");
let _merge = require("lodash.merge");

module.exports = {
    mixins: [require("./../../mixins/search-bar.js")],

    props: {
        dataButtonClass: {
            type: String,
            default: "btn btn-color5 btn-horizontal-input btn-ghost"
        }
    },

    data() {
        return {
            open: false,
            buttonClass: this.dataButtonClass
        };
    },

    methods: {
        emitSearchUpdated() {
            this.$emit("advanced-search-updated", this.search);
            window.Bus.$emit("advanced-search-updated", this.search);
        },

        businessCategoryChange(options) {
            this.search.business_categories = _map(
                options,
                option => option.value
            );
            this.emitSearchUpdated();
        },

        handleChange(input) {
            this.search[input.name] = input.value;
            this.emitSearchUpdated();
        }
    },

    mounted() {
        window.Bus.$on("listing-search-bar.search.updated", search => {
            this.search = search;
        });
    }
};
</script>
