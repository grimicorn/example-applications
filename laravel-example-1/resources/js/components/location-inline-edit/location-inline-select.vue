<template>
  <wrapper v-bind="$props">
    <!-- Value -->
    <template v-slot:value="{ value }">
      {{ getLabelForOptionValue(value) }}
    </template>

    <!-- Input -->
    <template
      v-slot:input="{
        inputId,
        currentValue,
        inputName,
        saving,
        setCurrentValue,
      }"
    >
      <input-fancy-select
        v-if="dataUseFancySelect"
        :dataName="inputName"
        :dataId="inputId"
        :value="currentValue"
        :dataOptions="dataOptions"
        @input="
          (selection) =>
            setCurrentValue(selection ? selection.value : undefined)
        "
      ></input-fancy-select>
      <select
        v-else
        :value="currentValue"
        @input="(event) => setCurrentValue(event.target.value)"
        :name="inputName"
        :id="inputId"
        :disabled="saving"
        class="pr-6"
      >
        <option
          v-for="{ value, label } in dataOptions"
          :value="value"
          v-text="label"
          :key="value"
        ></option>
      </select>
    </template>
  </wrapper>
</template>

<script>
import wrapper from "./_location-inline-edit-wrapper";

export default {
  components: {
    wrapper,
  },

  props: {
    ...wrapper.props,
    dataOptions: {
      type: Array,
      required: true,
      validator: function (options) {
        const validOptions = options.filter((option) => {
          const keys = Object.keys(option);
          return keys.includes("label") && keys.includes("value");
        });

        return options.length === validOptions.length;
      },
    },

    dataUseFancySelect: {
      type: Boolean,
      default: true,
    },
  },

  methods: {
    getLabelForOptionValue(value) {
      const options = JSON.parse(JSON.stringify(this.dataOptions));
      value = value ?? "";
      return options.filter((option) => {
        const optionValue = option.value ?? "";
        return optionValue.toString() === value.toString();
      })[0]?.label;
    },
  },
};
</script>
