<template>
  <div class="">
    <button
      type="button"
      @click="toggle"
      class="flex items-center button-xs button"
    >
      <icon data-name="lock-closed" class="w-2 h-2 mr-1"></icon>
      {{ label }}
    </button>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  props: {},

  data() {
    return {};
  },

  computed: {
    ...mapState(["globalMoreInfoOpen"]),

    icon() {
      return this.globalMoreInfoOpen ? "lock-open" : "lock-closed";
    },

    label() {
      return this.globalMoreInfoOpen
        ? "Close Info Sections"
        : "Open Info Sections";
    },
  },

  methods: {
    toggle() {
      this.$store.commit("setglobalMoreInfoOpen", !this.globalMoreInfoOpen);
    },
  },
};
</script>
