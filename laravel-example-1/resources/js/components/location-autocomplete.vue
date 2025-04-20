<template>
  <clearable-input
    type="search"
    :name="name"
    :id="id"
    :value="value"
    @input="handleInput"
    :placeholder="placeholder"
    autocomplete="off"
    @keypress.enter.prevent
  />
</template>

<script>
import initalizeAutocomplete from '@/libs/google/places-autocomplete';

export default {
  props: {
    name: {
      type: String,
      required: true,
    },
    id: {
      type: String,
    },
    value: {
      type: String,
      default: '',
    },
    placeholder: {
      type: String,
      default: '1111 Some St., St. Louis, MO 63129',
    },
  },

  data() {
    return {};
  },

  computed: {},

  mounted() {
    initalizeAutocomplete(
      this.$el.querySelector('input'),
      this.autocompleteCallback
    );
  },

  methods: {
    autocompleteCallback() {
      this.$emit('input', this.$el.querySelector('input').value);
    },

    handleInput(event) {
      this.$emit('input', event && event.target ? event.target.value : '');
    },
  },
};
</script>
