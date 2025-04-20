<template>
  <div class="inline-editable">
    <label>
      {{ dataLabel }}
      &nbsp;
      <button
        type="button"
        @click="toggle"
      >
        <span
          class="sr-only"
          v-text="`Edit ${dataLabel}`"
        ></span>
        <icon data-name="pencil-alt"></icon>
      </button>
    </label>

    <slot
      v-if="inputDisplayed"
      name="input"
    ></slot>

    <slot
      v-else
      name="value"
    ></slot>
  </div>
</template>

<script>
export default {
  props: {
    dataLabel: {
      type: String,
      required: true
    },

    dataInputDisplayed: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      inputDisplayed: this.dataInputDisplayed
    };
  },

  computed: {},

  methods: {
    toggle() {
      this.inputDisplayed = !this.inputDisplayed;
      this.$emit("toggled", this.inputDisplayed);
    }
  },

  watch: {
    dataInputDisplayed(newValue) {
      this.inputDisplayed = newValue;
    }
  }
};
</script>
