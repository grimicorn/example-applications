<style>
  @import "./InputCheckbox.css";
</style>

<template>
  <div
    class="input-wrap input-checkbox relative mb-6"
    :class="{
      'input-has-error': error,
      'input-disabled': disabled,
    }"
  >
    <div
      class="input-label-wrap flex items-center"
      :class="{
        'mb-2': instructions
      }"
    >
      <label>
        <input
          type="checkbox"
          :disabled="disabled"
          :readonly="readonly"
          :checked="dataChecked"
          v-model="inputValue"
        >
        <span
          v-if="dataIconName"
          class="label-icon"
        >
          <icon :data-name="dataIconName">
          </icon>
        </span>

        <span class="control-indicator"></span>
        {{ dataLabel }}
      </label>
    </div>

    <strong
      class="input-instructions"
      v-if="instructions"
      v-text="instructions"
    >
    </strong>

    <strong
      class="input-error"
      v-if="error"
      v-text="error"
    >
    </strong>
  </div>
</template>

<script>
// ========================================================================
// ** Important ***********************************************************
// ** If it's not in here its defined in /resources/js/mixins/input.js
// ========================================================================

import InputMixin from "@mixins/input.js";
export default {
  mixins: [InputMixin],

  props: {
    dataChecked: {
      type: Boolean,
      default: false
    },

    dataIconName: {
      type: String,
      default: ""
    }
  },

  data() {
    return {};
  },

  computed: {},

  methods: {}
};
</script>
