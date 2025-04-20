<template>
  <wrapper v-bind="$props">
    <template
      v-slot:input="{
        inputId,
        currentValue,
        inputName,
        saving,
        setCurrentValue,
      }"
    >
      <location-autocomplete
        :name="inputName"
        :id="inputId"
        :disabled="saving"
        :value="currentValue"
        @input="(value) => setCurrentValue(value)"
      ></location-autocomplete>
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
  },
};
</script>
