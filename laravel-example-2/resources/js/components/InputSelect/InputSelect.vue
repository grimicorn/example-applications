<style>
  @import "./InputSelect.css";
</style>

<template>
  <div
    class="input-wrap input-select relative mb-6"
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
      ></label>
      <div class="flex">
        <div class="select-wrap">
          <select
            :id="id"
            :name="name"
            v-model="inputValue"
            :disabled="disabled"
            :readonly="readonly"
            :required="dataRequired"
          >
            <option
              v-text="dataPlaceholder"
              v-if="dataPlaceholder"
              value=""
              :disabled="!dataClearable"
              class="select-placeholder"
              selected="selected"
            ></option>

            <option
              v-for="(label, value) in dataOptions"
              :key="value"
              :value="value"
            >
              <slot
                name="option"
                :label="label"
                :value="value"
              >
                {{ label }}
              </slot>
            </option>
          </select>
          <div class="select-dropdown">
            <div class="dropdown-arrow"></div>
          </div>
        </div>
      </div>
      <span class="control-indicator"></span>
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
    dataOptions: {
      type: Object,
      required: true
    },

    dataClearable: {
      type: Boolean,
      default: false
    },

    // A default value is required for the placeholder functionality. This overrides the value property set in mixins/input.js
    value: {
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
