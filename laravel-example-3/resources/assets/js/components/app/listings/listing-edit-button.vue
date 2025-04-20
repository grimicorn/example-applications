
<template>
  <div class="inline-block">
    <app-model-save-button
      @click="maybeDisplayEncouragementModal"
      isLink="false"
    ></app-model-save-button>
    <modal
      :data-is-open="modalOpen"
      @closed="modalOpen = false"
      modal-class="modal-width-small"
      title="Ready to Activate Page"
      :display-button="false"
      :display-cancel="true"
      cancel-label="Keep Working"
    >
      <template>
        <p class="text-center">
          Your business page is now ready to be activated so you can share it with others!
        </p>

        <p class="text-center">
          Activate your page now and you will be able to send the link to potential buyers. From there, an Exchange Space can be created and the diligence process can get underway.
        </p>

        <p class="text-center">
          You can update your listing with more information at any time.
        </p>

        <div class="text-center">
          <button
            type="button"
            class="inline-block"
            @click="redirectToPublish"
          >Post Business</button>
        </div>
      </template>
    </modal>
  </div>
</template>

<script>
export default {
  props: {
    dataShouldDisplayEncouragementModal: {
      type: Boolean,
      required: false
    },

    dataListingId: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      shouldDisplayEncouragementModal: this.dataShouldDisplayEncouragementModal,
      modalOpen: false,
      listingId: this.dataListingId,
      currentlyPublishing: false
    };
  },

  methods: {
    maybeDisplayEncouragementModal() {
      if (this.currentlyPublishing) {
        return;
      }

      if (!this.shouldDisplayEncouragementModal) {
        return;
      }

      this.modalOpen = true;
      this.disableListingShouldDisplayEncouragementModal();
    },

    disableListingShouldDisplayEncouragementModal() {
      this.shouldDisplayEncouragementModal = false;
      let route = `/dashboard/listing/display-encouragement-modal/${
        this.listingId
      }/destroy`;
      window.axios.delete(route);
    },

    redirectToPublish() {
      window.location = this.publishLink;
    }
  },

  computed: {
    publishLink() {
      return `/dashboard/listing/${
        this.listingId
      }/details/edit?enable_publish_modal=1`;
    }
  },

  mounted() {
    window.Bus.$on(
      "publish-modal.opened",
      () => (this.currentlyPublishing = true)
    );
    window.Bus.$on(
      "publish-modal.closed",
      () => (this.currentlyPublishing = false)
    );
  }
};
</script>
