<template>
  <button
    type="button"
    @click="this.open"
    class="w-20 h-20 max-w-full max-h-full"
  >
    <slot name="button" />
  </button>
  <teleport to="body" v-if="isOpen">
    <div
      class="fixed inset-0 z-50 flex items-center justify-center overflow-scroll bg-white bg-opacity-80"
    >
      <div class="relative max-w-full max-h-full p-10">
        <slot name="content" />
        <button
          type="button"
          class="absolute top-0 right-0 p-3 mt-6 mr-6 leading-none text-white rounded-full bg-primary-900"
          @click="close"
        >
          <icon data-name="close" class="w-4 h-4" />
          <span class="sr-only">Close</span>
        </button>
      </div>
    </div>
  </teleport>
</template>

<script>
export default {
  props: {
    dataIsOpen: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      isOpen: this.dataIsOpen,
    };
  },

  computed: {},

  watch: {
    dataIsOpen(value) {
      if (value) {
        this.open();
      } else {
        this.close();
      }
    },
  },

  methods: {
    open() {
      this.isOpen = true;
      document.body.classList.add("overflow-hidden");
      this.$emit("opened");
    },
    close() {
      this.isOpen = false;
      this.$emit("closed");
      document.body.classList.remove("overflow-hidden");
    },
  },

  mounted() {
    if (this.dataIsOpen) {
      this.open();
    }
  },
};
</script>
