<style scoped>
/* Hides default clear button */
input[type='search']::-webkit-search-decoration,
input[type='search']::-webkit-search-cancel-button,
input[type='search']::-webkit-search-results-button,
input[type='search']::-webkit-search-results-decoration {
  display: none;
}
</style>
<template>
  <div class="relative w-full">
    <input
      :type="type"
      @input="(value) => $emit('input', value)"
      class="w-full"
      :class="{ 'pr-10': value }"
      :name="name"
      :id="id"
      :value="value"
      v-bind="$attrs"
    />
    <button
      type="button"
      class="absolute top-0 right-0 flex items-center justify-center w-10 h-full border border-transparent  hover:bg-primary-900 hover:text-white"
      @click="handleClick"
      v-show="value"
    >
      <icon dataName="close" class="w-5 h-5" />
    </button>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,
  props: {
    type: {
      type: String,
      default: 'text',
    },
    name: {
      type: String,
      required: true,
    },
    value: {},
  },

  data() {
    return {};
  },

  computed: {
    id() {
      return this.$attrs.id ? this.$attrs.id : this.name;
    },
  },

  methods: {
    handleClick($e) {
      $e.currentTarget.blur();
      this.$emit('input', '');
    },
  },
};
</script>
