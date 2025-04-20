<template>
  <modal :data-is-open="isOpen" @closed="handleClosed" @opened="handleOpened">
    <template v-slot:button>
      <img
        loading="lazy"
        :src="dataThumbnailSrc"
        :alt="dataAlt"
        :width="dataThumbnailWidth"
        :height="dataThumbnailHeight"
        class="object-cover w-full h-full"
        :title="dataAlt"
      />
    </template>

    <template v-slot:content>
      <div
        class="absolute top-0 left-0 right-0 p-4 mx-10 mt-10 bg-white bg-opacity-50"
        v-if="!isLoading"
      >
        View on
        <a
          :href="dataOriginalLink"
          rel="noreferrer noopener"
          target="_blank"
          v-text="orginalLinkDomain"
          class="button-link"
        ></a>
      </div>

      <img
        :src="dataFullSrc"
        :alt="dataAlt"
        :width="dataFullWidth"
        :height="dataFullHeight"
        class="max-w-full max-h-full"
        @load="handleImageLoaded"
      />

      <div
        class="z-50 flex items-center justify-center max-w-full max-h-full p-10"
        v-if="isLoading"
      >
        <div
          style="margin-left: -67px; margin-top: -16px"
          class="fixed flex items-center text-xl text-primary-900 text-bold top-1/2 left-1/2"
        >
          <icon
            data-name="cog"
            class="w-8 h-8 mr-4 font-bold text-primary-900"
            data-animation="spin"
          ></icon>
          Loading...
        </div>
      </div>
    </template>
  </modal>
</template>

<script>
export default {
  props: {
    dataOriginalLink: {
      type: String,
    },
    dataIsOpen: {
      type: Boolean,
      default: false,
    },
    dataFullSrc: {
      type: String,
      required: true,
    },
    dataFullWidth: {
      type: Number,
      required: true,
    },
    dataFullHeight: {
      type: Number,
      required: true,
    },
    dataThumbnailSrc: {
      type: String,
      required: true,
    },
    dataThumbnailWidth: {
      type: Number,
      required: true,
    },
    dataThumbnailHeight: {
      type: Number,
      required: true,
    },
    dataAlt: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      isOpen: this.dataIsOpen,
      isLoading: true,
    };
  },

  computed: {
    orginalLinkDomain() {
      return new URL(this.dataOriginalLink).host.replace(/^www./g, "");
    },
  },

  watch: {
    dataIsOpen(value) {
      this.isOpen = value;
    },
  },

  methods: {
    handleOpened() {
      this.isLoading = true;
      this.isOpen = true;
      this.$emit("opened");
    },
    handleClosed() {
      this.isOpen = false;
      this.isLoading = false;
      this.$emit("closed");
    },
    handleImageLoaded() {
      this.isLoading = false;
    },
  },

  mounted() {
    if (this.dataIsOpen) {
      this.open();
    }
  },
};
</script>
