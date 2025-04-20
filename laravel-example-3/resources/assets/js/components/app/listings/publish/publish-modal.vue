<template>
  <div v-if="listing.id === 0">
    <input
      type="hidden"
      name="enable_publish_modal"
      value="1"
      v-if="enablePublishModal"
    >
    <button
      type="submit"
      class="btn Open model-publish-button"
      @click="enablePublishModal = true"
    >Post Business</button>
  </div>

  <div v-else>
    <modal
      button-label="Activate Page"
      :title="modalTitle"
      @closed="modalClose"
      @opened="modalOpen"
      modal-title-class="mb3"
      :modal-class="modalClass"
      :display-button="!listing.published"
      :button-disabled="!subscriptionChecked || !republishableChecked"
      :auto-open="autoOpen"
    >
      <template scope="props">
        <div v-if="autoPublishChecked">
          <!-- Confirmation -->
          <app-listing-publish-confirmation
            v-if="confirming"
            @canceled="publishCanceled(props.close)"
            @confirmed="publishConfirmed()"
          >
            <!-- Monthly -->
            <p v-if="confirmingMonthly">
              You are currently subscribed to a monthly plan.
            </p>

            <!-- Per-Listing -->
            <p v-if="confirmingPerListing">
              You have previously paid for this business.
            </p>
          </app-listing-publish-confirmation>

          <!-- Publish Loader -->
          <div
            v-if="publishing"
            class="publish-modal-publishing text-center pa1"
          >
            <loader
              class="inline-block"
              :cover="false"
              :loading="true"
            ></loader>
          </div>

          <!-- Pricing Options -->
          <app-listing-publish-pricing-options
            :listing="listing"
            v-if="stage === 1 && !publishing && !confirming"
            @plan-selected="planSelected"
          ></app-listing-publish-pricing-options>

          <!-- Per-Listing Payment Form -->
          <div
            class="publish-modal-form subscription-form"
            v-if="showPerlistingForm"
          >
            <slot name="per-listing-form"></slot>
          </div>

          <!-- Monthly Subscription Payment Form -->
          <div
            class="publish-modal-form subscription-form"
            v-if="showMonthlyForm"
          >
            <slot name="monthly-form"></slot>
          </div>

          <!-- Monthly Small Subscription Payment Form -->
          <div
            class="publish-modal-form subscription-form"
            v-if="showMonthlyFormSmall"
          >
            <slot name="monthly-form-small"></slot>
          </div>

          <!-- Complete -->
          <div
            class="text-center"
            v-if="stage === 3 && !publishing && !confirming"
          >
            <div class="fc-color4 text-bold mb3">
              Congraulations! Your listing has been posted.
            </div>

            <div class="inline-block">
              <a
                :href="listing.show_url"
                target="_blank"
                class="btn mr3"
              >View Business</a>

              <a
                href="/dashboard/listings"
                class="btn btn-color5"
              >Back to Businesses</a>
            </div>
          </div>
        </div>

        <div
          v-else
          class="text-center pa1"
        >
          <loader
            class="inline-block"
            :loading="true"
            :cover="false"
          ></loader>
        </div>
      </template>
    </modal>

    <div
      class="relative"
      v-if="listing.published"
    >
      <loader :loading="unpublishing"></loader>

      <button
        @click.prevent="unpublish"
        type="button"
        :disabled="unpublishing"
      >Deactivate Page</button>
    </div>
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

    autoOpen: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      enablePublishModal: false,
      stage: 1,
      planId: "",
      isSubscribed: false,
      isRepublishable: false,
      publishing: false,
      confirmingMonthly: false,
      confirmingPerListing: false,
      unpublishing: false,
      subscriptionChecked: false,
      republishableChecked: false,
      formId: "application-listings-details"
    };
  },

  computed: {
    modalClass() {
      if (
        !this.autoPublishChecked ||
        this.publishing ||
        this.stage === 3 ||
        this.stage === 1
      ) {
        return "modal-width-small";
      }

      if (this.confirming) {
        return "modal-content-medium";
      }

      return "modal-content-large";
    },

    confirming: {
      get() {
        return this.confirmingMonthly || this.confirmingPerListing;
      },

      set() {}
    },

    unpublishUrl() {
      return `/dashboard/listing/${this.listing.id}/unpublish`;
    },

    publishUrl() {
      return `/dashboard/listing/${this.listing.id}/publish`;
    },

    showForm() {
      return this.stage === 2 && !this.publishing && !this.confirming;
    },

    showMonthlyForm() {
      return this.showForm && this.planId === this.monthlyPlanId;
    },

    showMonthlyFormSmall() {
      return this.showForm && this.planId === this.monthlyPlanIdSmall;
    },

    showPerlistingForm() {
      return false;
      return this.showForm && this.planId !== this.monthlyPlanId;
    },

    autoPublishChecked() {
      return this.subscriptionChecked && this.republishableChecked;
    },

    modalTitle() {
      if (!this.autoPublishChecked) {
        return "Checking Your Subscription";
      }

      if (this.publishing) {
        return "Publishing Your Business";
      }

      if (this.confirming) {
        return "Post Business";
      }

      if (this.stage === 1) {
        return "Pricing";
      }

      if (this.stage === 2) {
        return "Payment Method";
      }

      if (this.stage === 3) {
        return "Congratulations";
      }

      return "Business Publish";
    }
  },

  methods: {
    unpublish() {
      if (this.unpublishing) {
        return;
      }

      this.unpublishing = true;

      window.axios
        .delete(this.unpublishUrl)
        .then(({ data }) => {
          this.unpublishing = false;
          window.flashAlert(data.status, {
            type: "success",
            timeout: 5000
          });
          this.listing.published = false;

          window.Bus.$emit("listing-unpublished");
        })
        .catch(() => {
          this.unpublishing = false;
          window.flashAlert(
            "Business could not be unpublished please try again.",
            { type: "error" }
          );
        });
    },

    removeAutoOpenParam() {
      let href = window.location.href
        .replace(/enable_publish_modal=[a-z0-9A-Z]/g, "")
        .replace("?&", "?")
        .replace("&&", "&");

      window.history.replaceState({}, document.title, href);
    },

    reset() {
      this.planId = "";
      this.stage = 1;
      this.confirming = false;
      this.confirmingMonthly = false;
      this.confirmingPerListing = false;
      this.publishing = false;
      this.unpublishing = false;
      this.removeAutoOpenParam();
    },

    modalOpen() {
      // Go ahead and try to publish the listing on open
      // if the user is paid for the listing previously.
      if (this.isRepublishable) {
        this.confirmPerListingPublish();
      }

      // Go ahead and try to publish the listing on open
      // if the user is subscribed.
      if (this.isSubscribed) {
        this.confirmMonthlyPublish();
      }

      window.Bus.$emit("publish-modal.opened");
    },

    modalClose() {
      this.reset();
      window.Bus.$emit("publish-modal.closed");
    },

    publishCanceled(modalClose) {
      // Let the world know.
      this.$emit("publish-confirmed", false);

      // Reset all the things.
      this.reset();

      // Close the modal
      modalClose();
    },

    publishConfirmed() {
      this.$emit("publish-confirmed", true);
    },

    confirmMonthlyPublish() {
      this.confirmPublish("confirmingMonthly");
    },

    confirmPerListingPublish() {
      this.confirmPublish("confirmingPerListing");
    },

    confirmPublish(property) {
      this[property] = true;
      this.$on("publish-confirmed", confirmed => {
        this[property] = false;

        if (confirmed) {
          this.publish();
        }
      });
    },

    publish() {
      if (this.publishing) {
        return;
      }

      this.publishing = true;

      window.Bus.$on(`${this.formId}.successfully-submitted`, () => {
        this.publishRequest();
      });

      window.Bus.$on(`${this.formId}.submit-error`, () => {
        this.reset();
      });

      let form = document.getElementById(this.formId);
      if (form !== null) {
        form.querySelectorAll(".btn-model-submit")[0].click();
      } else {
        this.publishRequest();
      }
    },

    finalize() {
      this.publishing = false;
      this.stage = 3;
      this.listing.published = true;
      this.isRepublishable = true;
      window.Bus.$emit("listing-published");
    },

    publishRequest() {
      // Try to publish if there is an error call this.reset();
      window.axios
        .post(this.publishUrl)
        .then(() => {
          this.finalize();
        })
        .catch(() => {
          this.reset();
        });
    },

    checkSubscription() {
      window.axios.get("/dashboard/profile/subscription").then(({ data }) => {
        this.isSubscribed = data.status === "subscribed";
        this.subscriptionChecked = true;
      });
    },

    checkRepublishable() {
      window.axios
        .get(`/dashboard/listing/${this.listing.id}/republishable`)
        .then(({ data }) => {
          this.isRepublishable = !!data.republishable;
          this.republishableChecked = true;
        });
    },

    planSelected(planId) {
      this.planId = planId.toString().trim();
      this.stage = this.planId ? 2 : 1;
    }
  },

  watch: {
    autoPublishChecked(value) {
      if (value && this.autoOpen) {
        this.modalOpen();
      }
    }
  },

  created() {
    this.checkSubscription();
    this.checkRepublishable();

    window.Bus.$on("payment-form:update", () => {
      window.Bus.$on("updateUser", () => {
        this.publish();
      });
    });
  }
};
</script>
