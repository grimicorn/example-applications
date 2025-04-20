
<template>
  <div
    class="text-center mb2 fz-18 ba1 pa3 bg-color12"
    v-if="published"
  >
    <span class="fc-color5">BUSINESS POSTED!</span> Your business has been posted and is now live for potential buyers to see. Share the <a :href="listing.show_url" target="_blank">link to your business's page</a> with perspective buyers and they will be able to start an Exchange Space with you.
    <br><br>
    You may continue to add or change information at any time. This includes adding the business's historical financial information, which can be accessed via the Historical Financials tab above.
  </div>
  <div
    class="text-center mb2 fz-18 ba1 pa3 bg-color12"
    v-else
  >
    <span class="fc-color5">Ready to share this business?</span> Click the Activate Page button to make the summary page for this business live so you can share it with prospective buyers. You can add additional detail at any time.
  </div>
</template>

<script>
export default {
  props: {
    dataPublished: {
      type: Boolean,
      default: false
    },
    listing: {
      type: Object,
      required: true
    },
  },

  data() {
    return {
      published: this.dataPublished
    };
  },

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
