<template>
    <div class="listing-cards">
        <app-listing-card
        v-for="(listing, index) in listings"
        :key="listing.id"
        :listing="listing"
        :even="((index + 1) % 2 === 0) ? true : false"
        :score="listing.current_score_total_percentage"
        :user-exchange-space="{}"
        ></app-listing-card>
        <br>
        <alert :dismissible="false">
        As a recently launched platform, we are investing heavily in a multi-channel digital advertising campaign designed expressly to grow a community of serious buyers and sellers. While you may not have found the business you're looking for today, we're confident you will soon.
        <br>
        <br>
        Looking to buy a business? Click "Save Search" to be automatically notified when a listing matching your search criteria is posted.
        <br>
        <br>
        Looking to sell a business? Get exposure for your business unlike you would anywhere else as one of the first listings on the platform! To celebrate our launch, we are offering introductory rates of 50% off for a limited time.
        </alert>

    </div>
</template>

<script>
export default {
    props: {
        dataListings: {
            type: [Array, Object],
            required: true
        }
    },

    data() {
        return {};
    },

    computed: {
        listings() {
            return Object.values(this.dataListings.data);
        }
    },

    methods: {}
};
</script>
