<template>
  <div class="flex items-center w-full md:w-1/4" v-if="locationIcons">
    <input-fancy-select
      dataPlaceholder="Select a custom icon"
      :dataName="dataName"
      :dataId="dataAttributes.id ? dataAttributes.id : dataName"
      v-bind="dataAttributes"
      :value="currentValue"
      :dataOptions="selectOptions"
      @input="handleSelectInput"
    ></input-fancy-select>
    <location-icon
      :data-icon="selectedIcon"
      :data-show-default="false"
      class="ml-2"
    ></location-icon>
  </div>
</template>

<script>
import collect from "collect.js";
import { mapState } from "vuex";

export default {
  props: {
    dataName: {
      type: String,
      required: true,
    },
    dataAttributes: {
      type: Object,
      default() {
        return {};
      },
    },
    value: {
      type: [String, Number],
      default: "",
    },
  },

  data() {
    return {
      currentValue: this.value,
    };
  },

  computed: {
    ...mapState(["locationIcons"]),

    selectedIcon() {
      const icon = collect(this.locationIcons)
        .where("id", parseInt(this.currentValue, 10))
        .first();

      return icon ? icon : undefined;
    },

    selectOptions() {
      return this.locationIcons.map((item) => {
        return {
          label: item.name,
          value: item.id,
        };
      });
    },
  },

  watch: {
    value(value) {
      this.currentValue = value;
    },
  },

  methods: {
    handleSelectInput(selection) {
      this.currentValue = selection ? selection.value : undefined;
      this.$emit("input", this.currentValue);
    },
  },
};
</script>
