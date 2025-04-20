<style>
  @import "./InputTextarea.css";
</style>

<template>
  <div
    class="input-wrap input-text-area relative mb-6"
    :class="{
      'input-has-error': error,
      'input-disabled': disabled,
    }"
  >
    <div
      class="input-label-wrap"
      :class="{
        'mb-2': instructions
      }"
    >
      <label
        :for="id"
        v-text="dataLabel"
        class="block"
      ></label>
      <div class="flex">
        <textarea
          :id="id"
          :name="name"
          v-model="inputValue"
          :disabled="disabled"
          :readonly="readonly"
          :placeholder="dataPlaceholder"
          :required="dataRequired"
          :rows="dataRows"
        ></textarea>
      </div>

      <div
        v-if="$slots['save-button']"
        class="pt-4"
      >
        <button
          type="button"
          @click="save"
          class="button"
          :disabled="disabled"
        >
          <slot name="save-button"></slot>
        </button>
      </div>
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
    dataIconName: {
      type: String,
      default: ""
    },

    dataRows: {
      type: Number,
      default: 5
    }
  },

  data() {
    return {
      shouldAutoSave: typeof this.$slots["save-button"] !== "undefined"
    };
  },

  computed: {},

  methods: {}
};
</script>
