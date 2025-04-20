
<template>
  <div
    class="ml2"
    v-if="published"
  >
    <i
      class="fa fa-check-circle fa-lg alert-posted"
      aria-hidden="true"
    ></i> Status: Activated
  </div>
  <div
    class="ml2"
    v-else
  >
    <i
      class="fa fa-exclamation-triangle fa-lg alert-notposted"
      aria-hidden="true"
      style
    ></i> Status: Not Activated
  </div>
</template>

<script>
export default {
  props: {
    dataPublished: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      published: this.dataPublished
    };
  },

  computed: {},

  methods: {},

  mounted() {
    window.Bus.$on("listing-unpublished", () => {
      this.published = false;
    });

    window.Bus.$on("listing-published", () => {
      this.published = true;
    });
  }
};
</script>
