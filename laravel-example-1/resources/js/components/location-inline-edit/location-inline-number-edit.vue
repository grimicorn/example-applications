<template>
  <wrapper v-bind="$props">
    <template v-slot:value="{ value }">
      {{ defaultValue(value) }}
    </template>
    <template
      v-slot:input="{
        inputId,
        currentValue,
        inputName,
        saving,
        setCurrentValue,
      }"
    >
      <input
        type="number"
        :value="currentValue"
        @input="(event) => setCurrentValue(event.target.value)"
        :name="inputName"
        :id="inputId"
        :disabled="saving"
        :min="dataMin"
        :max="dataMax"
        :step="dataStep"
      />
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
    dataMin: {
      type: Number,
    },
    dataMax: {
      type: Number,
    },
    dataStep: {
      type: Number,
    },
    dataDisplaySuffix: {
      type: String,
    },
  },

  methods: {
    defaultValue(value) {
      return isNaN(parseFloat(value))
        ? "Unknown"
        : `${value} ${this.dataDisplaySuffix}`.trim();
    },
  },
};
</script>
