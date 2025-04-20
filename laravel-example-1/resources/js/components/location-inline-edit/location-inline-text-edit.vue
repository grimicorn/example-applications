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
      <input
        type="text"
        :value="currentValue"
        @input="(event) => setCurrentValue(event.target.value)"
        :name="inputName"
        :id="inputId"
        :disabled="saving"
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
  },
};
</script>
