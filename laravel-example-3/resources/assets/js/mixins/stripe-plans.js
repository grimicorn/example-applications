module.exports = {
    computed: {
        perListingId() {
            let planId = window.Spark.stripe.per_listing_plan_id;
            return typeof planId === 'undefined'
                ? ''
                : planId.toString().trim();
        },

        monthlyPlanId() {
            let planId = window.Spark.stripe.monthly_plan_id;
            return typeof planId === 'undefined'
                ? ''
                : planId.toString().trim();
        },

        monthlyPlanIdSmall() {
            let planId = window.Spark.stripe.monthly_plan_id_small;
            return typeof planId === 'undefined'
                ? ''
                : planId.toString().trim();
        },
    },
};
