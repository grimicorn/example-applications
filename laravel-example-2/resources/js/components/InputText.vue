<template>
  <div
    class="input-wrap input-search relative mb-6"
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
        <input
          :id="id"
          :name="name"
          :type="dataType"
          v-model="inputValue"
          :disabled="disabled"
          :readonly="readonly"
          :placeholder="dataPlaceholder"
          :required="dataRequired"
          :min="dataMin"
          :max="dataMax"
          :step="dataStep"
          class="appearance-none border border-black rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        >
        <icon
          v-if="dataIconName"
          :data-name="dataIconName"
        >
        </icon>
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
import dateToLocalTimezone from "@utilities/dateToLocalTimezone";
import { format } from "date-fns";
import InputMixin from "@mixins/input.js";
export default {
  mixins: [InputMixin],

  props: {
    dataIconName: {
      type: String,
      default: ""
    },

    dataType: {
      type: String,
      default: "text"
    },

    dataMin: {
      type: Number
    },

    dataMax: {
      type: Number
    },

    dataStep: {
      type: Number
    }
  },

  data() {
    return {};
  },

  computed: {},

  methods: {
    customStandardizeValue(value) {
      if (!value) {
        return value;
      }

      if (this.dataType === "date") {
        value = dateToLocalTimezone(value, "YYYY-MM-DD");
      }

      if (this.dataType === "datetime-local") {
        value = dateToLocalTimezone(value, "YYYY-MM-DDTHH:mm");
      }

      return value;
    },

    customFormatForSave(value) {
      if (!value) {
        return value;
      }

      if (["date", "datetime-local"].includes(this.dataType)) {
        value = format(value, "YYYY-MM-DD HH:mm:ss");
      }

      return value;
    }
  }
};
</script>
