<template>
  <div class="modal-component">
    <button
      type="button"
      v-html="buttonLabel"
      v-if="displayButton"
      class="btn"
      :class="buttonClass"
      :disabled="buttonDisabled"
      @click="open"
    ></button>

    <div
      class="modal-background"
      v-if="isOpen || useShow"
      v-show="isOpen || !useShow"
      @click="close"
    >
      <div
        class="modal-content"
        :class="modalClass"
        @click.stop
      >
        <h2
          v-text="title"
          v-if="title"
          :class="modalTitleClass"
          class="modal-title text-center"
        ></h2>

        <span
          class="modal-close"
          @click="close"
        >&times;</span>

        <alerts :in-modal="true"></alerts>

        <slot
          :close="close"
          :open="open"
        ></slot>

        <div
          v-if="displayCancel"
          class="text-center pt3"
        >
          <button
            @click="close"
            :class="cancelButtonClass"
            v-text="cancelLabel"
          ></button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
module.exports = {
  props: {
    dataIsOpen: {
      type: Boolean,
      default: false
    },

    displayCancel: {
      type: Boolean,
      default: false
    },

    cancelLabel: {
      type: String,
      default: "Cancel"
    },

    buttonDisabled: {
      type: Boolean,
      default: false
    },

    autoOpen: {
      type: Boolean,
      default: false
    },

    title: {
      type: String,
      default: ""
    },

    buttonLabel: {
      type: String,
      default: "Open"
    },

    buttonClass: {
      type: String,
      default: "Open"
    },

    displayButton: {
      type: Boolean,
      default: true
    },

    modalTitleClass: {
      type: String,
      default: ""
    },

    useShow: {
      type: Boolean,
      default: false
    },

    modalClass: {
      type: String,
      default: ""
    },

    dataModalId: {
      type: String,
      default: ""
    },

    dataCancelButtonClass: {
      type: String,
      default: "btn btn-link a-ul inline-block text-italic"
    }
  },

  data() {
    return {
      isOpen: this.dataIsOpen,
      modalId: this.dataModalId,
      cancelButtonClass: this.dataCancelButtonClass
    };
  },

  computed: {},

  methods: {
    setOverflow(overflow) {
      document.getElementsByTagName("body")[0].style.overflow = overflow;
    },

    open() {
      this.isOpen = true;
      this.setOverflow("hidden");
      this.$emit("opened");
      window.Bus.$emit("modal-opened");
    },

    close() {
      this.isOpen = false;
      this.setOverflow("");
      this.$emit("closed");
      window.Bus.$emit("modal-closed");
    }
  },

  watch: {
    dataIsOpen(newValue) {
      if (newValue) {
        this.open();
      } else {
        this.close();
      }
    }
  },

  mounted() {
    if (this.autoOpen) {
      this.open();
    }

    window.Bus.$on("modal-should-close", this.close);

    if (this.modalId) {
      window.Bus.$on(`modal-should-open.${this.modalId}`, this.open);
      window.Bus.$on(`modal-should-close.${this.modalId}`, this.close);
    }
  }
};
</script>
