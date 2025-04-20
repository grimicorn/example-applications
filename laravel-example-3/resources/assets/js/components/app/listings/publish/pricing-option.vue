<template>
  <div
    class="pricing-option relative"
    v-if="price"
  >


    <h3
      v-text="title"
      class="pricing-option-title"
    ></h3>

    <div class="pricing-option-price-wrap text-center">
      <span
        class="price"
        v-if="per"
      >{{ price | price }}/{{ per }}</span>

      <span
        class="price"
        v-else
      >{{ price | price }}</span>
    </div>

    <div class="pricing-option-content text-center">
      <slot name="content"></slot>
    </div>

    <div class="pricing-option-ideal-for text-center">
      <slot name="ideal-for"></slot>
    </div>

    <div class="text-center pricing-option-confirm">
      <button
        type="button"
        @click="selected"
      >
        Buy Now
      </button>
    </div>

    <div class="pricing-option-after-confirm">
      <slot name="after-confirm"></slot>
    </div>
  </div>

  <div
    style="min-height: 40px;"
    class="pricing-option relative loading"
    v-else
  >
    <loader :loading="true"></loader>
  </div>
</template>

<script>
let filters = require("./../../../../mixins/filters.js");
module.exports = {
  mixins: [filters],

  props: {
    title: {
      type: String,
      required: true
    },

    per: {
      type: String,
      required: ""
    },

    planId: {
      type: String,
      required: true
    }
  },

  data() {
    return {
      price: 0,
      priceLoading: false,
      plansUrl: "/dashboard/profile/subscriptions"
    };
  },

  computed: {},

  methods: {
    selected() {
      this.$emit("selected", this.planId);
    },

    setPrice() {
      window.axios.get(this.plansUrl).then(({ data }) => {
        if (typeof data[this.planId] === "undefined") {
          return;
        }

        let amount = parseFloat(data[this.planId].amount, 10);
        if (isNaN(amount)) {
          return;
        }

        this.$emit("loaded");

        this.price = amount;
      });
    }
  },

  created() {
    this.setPrice();
  }
};
</script>
