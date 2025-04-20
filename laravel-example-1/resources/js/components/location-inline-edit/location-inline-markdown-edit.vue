<template>
  <wrapper v-bind="$props" class="w-full">
    <!-- Value -->
    <template v-slot:value="{ value }">
      <markdown :data-content="value" v-if="value"></markdown>
      <span v-else class="text-gray-600">Click pencil icon to add notes</span>
    </template>

    <!-- Label -->
    <template v-slot:label></template>

    <!-- Input -->
    <template
      v-slot:input="{
        inputId,
        currentValue,
        inputName,
        saving,
        setCurrentValue,
        label,
      }"
    >
      <markdown-editor
        :data-label="label"
        :data-name="inputName"
        :value="currentValue"
        :data-id="inputId"
        :data-disabled="saving"
        @input="(value) => setCurrentValue(value)"
      ></markdown-editor>
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
