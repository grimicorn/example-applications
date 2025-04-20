<template>
  <div
    class="relative outline-none"
    ref="dropdown_container"
    v-on-clickaway="close"
    @keyup.esc="close"
    tabindex="0"
  >
    <slot
      name="toggle"
      :toggle="toggle"
      :isOpen="isOpen"
    ></slot>

    <popper
      :data-is-visible="isOpen"
      data-placement="bottom-end"
      @update="handlePopperUpdate"
      @create="handlePopperCreate"
    >
      <div
        class="dropdown-card"
        v-show="isOpen"
        ref="dropdown"
      >
        <slot name="dropdown"></slot>
        <div class="popper__arrow"></div>
      </div>
    </popper>
  </div>
</template>

<script>
import { mixin as clickaway } from "vue-clickaway";

export default {
  mixins: [clickaway],

  props: {
    dataIsOpen: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      isOpen: this.dataIsOpen
    };
  },

  computed: {},

  watch: {
    dataIsOpen(newValue) {
      this.isOpen = newValue;
    },

    isOpen(newValue) {
      if (newValue) {
        this.$el.focus();
      }
    }
  },

  methods: {
    handlePopperUpdate(dataObject) {
      console.log(dataObject);
    },

    handlePopperCreate(dataObject) {
      console.log(dataObject);
    },

    toggle() {
      this.isOpen = !this.isOpen;
      this.$emit("toggled", this.isOpen);
    },

    close() {
      this.isOpen = false;
      this.$emit("toggled", this.isOpen);
    }
  },

  mounted() {
    if (this.isOpen) {
      this.$el.focus();
    }
  }
};
</script>
