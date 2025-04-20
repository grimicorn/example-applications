<template>
  <tool-tip
    :data-icon-class="dataIconClass"
    class="lc-rating-tooltip text-normal"
  >
      LC Ratings reflect the completeness of a listing, with more stars meaning more information has been provided. By default, search results are sorted by LC Rating with the highest at the top. <a href="/lc-rating/">Learn more here</a>.
  </tool-tip>
</template>

<script>
export default {
  props: {
    dataIconClass: {
        type: String,
        default: "fc-color4"
    }
  },

  data() {
    return {};
  },

  computed: {},

  methods: {},
};
</script>
