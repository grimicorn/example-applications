<template>
  <div class="listing-publish-pricing-options flex">
    <app-listing-publish-pricing-option
        v-show="!showLifetime"
        title="Monthly Subscription"
        :plan-id="monthlyPlanIdSmall"
        per="month"
        @selected="selected"
        @loaded="option1Loaded = true"
    >
        <template slot="content">
        Our monthly subscription provides unlimited access to all of our deal management and productivity tools. This includes the ability to add three active businesses and create an unlimited number of Exchange Spaces with potential buyers.
        </template>

        <template slot="ideal-for">
        Great for business brokers selling multiple businesses.
        </template>

    </app-listing-publish-pricing-option>
    <app-listing-publish-pricing-option
      v-show="!showLifetime"
      title="Unlimited Subscription"
      :plan-id="monthlyPlanId"
      per="month"
      @selected="selected"
      @loaded="option1Loaded = true"
    >
      <template slot="content">
        Our monthly subscription provides unlimited access to all of our deal management and productivity tools. This includes the ability to add unlimited businesses and create an unlimited number of Exchange Spaces with potential buyers.
      </template>

      <template slot="ideal-for">
        Great for business brokers selling multiple businesses.
      </template>

    </app-listing-publish-pricing-option>
  </div>
</template>

<script>
module.exports = {
  mixins: [require("./../../../../mixins/stripe-plans.js")],

  props: {
    listing: {
      type: Object,
      required: true
    },

    dataShowLifetime: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      option1Loaded: false,
      option2Loaded: false,
      showLifetime: this.dataShowLifetime
    };
  },

  computed: {
    showSeal() {
      return this.option1Loaded && this.option2Loaded;
    }
  },

  methods: {
    selected(planId) {
      this.$emit("plan-selected", planId);
    }
  }
};
</script>
