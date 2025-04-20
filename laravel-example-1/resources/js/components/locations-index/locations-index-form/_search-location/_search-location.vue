<template>
  <div class="flex items-center w-full">
    <span class="mr-2">Search Location:</span>
    <search-location-select
      @input="(value) => $emit('input', value)"
      class="px-3 py-1 border-r-2 border-primary-900"
    ></search-location-select>
    <search-location-add
      :dataAddress="dataAddress"
      class="px-3 py-1 border-r-2 border-primary-900"
    ></search-location-add>
    <search-location-delete class="px-3 py-1"></search-location-delete>
  </div>
</template>

<script>
import SearchLocationSelect from "./_search-location-select";
import SearchLocationAdd from "./_search-location-add";
import SearchLocationDelete from "./_search-location-delete";

export default {
  components: {
    SearchLocationAdd,
    SearchLocationSelect,
    SearchLocationDelete,
  },

  props: {
    dataAddress: {
      type: String,
    },
  },

  data() {
    return {};
  },

  computed: {},

  methods: {},
};
</script>
