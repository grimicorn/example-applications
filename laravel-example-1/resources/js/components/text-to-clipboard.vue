<style scoped>
svg {
  height: 0.9em;
  width: auto;
  max-height: 100%;
}

button {
  cursor: pointer;
}
</style>
<template>
  <button
    class="flex items-center"
    type="button"
    @click.prevent="handleClick"
    :disabled="copied"
    ref="button"
    :data-clipboard-text="dataText"
    :title="label"
  >
    <slot></slot>
    <span class="sr-only" v-text="label" v-if="slotEmpty"></span>
    <icon
      :data-name="iconName"
      class="w-4 h-4"
      :class="{
        'text-success': copied,
        'ml-1': !slotEmpty,
      }"
    ></icon>
  </button>
</template>

<script>
import ClipboardJS from "clipboard";

export default {
  props: {
    dataText: {
      type: String,
      required: true,
    },
    dataLabel: {
      type: String,
    },
  },

  data() {
    return {
      copied: false,
      clipboard: undefined,
    };
  },

  computed: {
    iconName() {
      return this.copied ? "checkmark" : "edit-copy";
    },
    label() {
      return this.dataLabel
        ? this.dataLabel
        : `Copy "${this.dataText}" to clipboard`;
    },
    slotEmpty() {
      return this.$slots.default === undefined;
    },
  },

  methods: {
    handleClick() {
      if (!this.clipboard || this.copied) {
        return;
      }

      this.clipboard.on("success", (e) => {
        this.copied = true;
        setTimeout(() => (this.copied = false), 2000);
        e.clearSelection();
      });
    },
  },

  mounted() {
    this.clipboard = new ClipboardJS(this.$refs["button"]);
  },
};
</script>
