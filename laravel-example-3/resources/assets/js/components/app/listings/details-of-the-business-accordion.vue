<template>
    <app-form-accordion
    header-title="Business Description"
    class="listing-details-details-of-the-business">

        <div slot="content">

            <!-- Products Services -->
            <input-textual
            type="textarea"
            name="products_services"
            :value="listing.products_services"
            placeholder="Products & Services"
            label="Products & Services"
            wrap-class="clear"
            tooltip="Describe the products and services the business provides."></input-textual>

            <!-- Market Overview -->
            <input-textual
            type="textarea"
            name="market_overview"
            :value="listing.market_overview"
            placeholder="Market Overview"
            label="Market Overview"
            wrap-class="clear"
            tooltip="Provide an analysis of the current market environment the business operates in."></input-textual>

            <!-- Competitive Position -->
            <input-textual
            type="textarea"
            name="competitive_position"
            :value="listing.competitive_position"
            placeholder="Competitive Position"
            label="Competitive Position"
            wrap-class="clear"
            tooltip="Breakdown the business’s competitive advantages and disadvantages, where applicable."></input-textual>

            <!-- Business Performance Outlook -->
            <input-textual
            type="textarea"
            name="business_performance_outlook"
            :value="listing.business_performance_outlook"
            placeholder="Performance & Outlook"
            label="Performance & Outlook"
            wrap-class="clear"
            tooltip="Help potential buyers understand recent trends and the future direction of the business."></input-textual>


        </div>
    </app-form-accordion>
</template>

<script>
module.exports = {
    props: {
        dataListing: {
            type: Object,
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
            listing: this.dataListing
        };
    },

    computed: {},

    methods: {}
};
</script>
