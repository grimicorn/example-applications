<template>
    <div class="lcr-wrap flex">
        <span class="lcr-wrap-label" v-text="label" v-if="enableLabel"></span>

        <span
            class="lcr-star-stack lcr-star-wrap"
            style="width:1em"
            v-for="count in starCounts"
            :key="count"
        >

            <i class="lcr-star lcr-star-empty"></i>
            <i
            v-if="isFilledStar(count)"
            class="lcr-star"
            :class="{
                'lcr-star-whole': isWholeStar(count),
                'lcr-star-half': isHalfStar(count),
            }"></i>
        </span>
    </div>
</template>

<script>
let _map = require('lodash.map');

export default {
    props: {
        dataPercentageComplete: {
            type: Number,
            default: 0,
        },

        dataEnableLabel: {
            type: Boolean,
            default: true,
        },
    },

    data() {
        return {
            enableLabel: this.dataEnableLabel,
        };
    },

    computed: {
        starCounts() {
            return _map([...Array(5).keys()], count => count + 1);
        },

        percentageComplete() {
            let complete = parseFloat(this.dataPercentageComplete, 10);

            return complete > 1 ? complete / 100 : complete;
        },

        label() {
            return this.rating.toFixed(2);
        },

        rating() {
            return this.percentageComplete * 5;
        },

        starRating() {
            let rating = this.rating;

            // Handle whole numbers.
            if (this.isWholeNumber(rating)) {
                return rating;
            }

            // Handle numbers > #.0 and <= #.5
            if (this.lessThanOrEqualToHalf(rating)) {
                return Math.floor(rating) + 0.5;
            }

            // Handle numbers > #.5 and < #.0
            return Math.floor(rating) + 1;
        },
    },

    methods: {
        isWholeNumber(value) {
            value = parseFloat(value, 10);
            return Math.floor(value) === value;
        },

        lessThanOrEqualToHalf(value) {
            value = parseFloat(value, 10);

            return value - 0.5 <= Math.floor(value);
        },

        isWholeStar(count) {
            // We will need whole stars until the last star which could be whole or half.
            return (
                this.isWholeNumber(this.starRating) || this.starRating > count
            );
        },

        isHalfStar(count) {
            // A whole rating number will be a half star...
            if (this.isWholeNumber(this.starRating)) {
                return false;
            }

            // Since we are "halfing" the last star we need to bump the rating to match the whole count.
            return Math.floor(this.starRating) + 1 === count;
        },

        isFilledStar(count) {
            return this.starRating > 0 && Math.ceil(this.starRating) >= count;
        },
    },
};
</script>
