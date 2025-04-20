<template>
  <ul>
    <li class="mb-4" v-for="location in locations" :key="location.id">
      <locations-index-list-item
        :data-location="location"
      ></locations-index-list-item>
    </li>
  </ul>
</template>

<script>
import collect from "collect.js";
import { mapState } from "vuex";

export default {
  props: {},

  data() {
    return {};
  },

  computed: {
    ...mapState(["paginatedLocations"]),
    locations() {
      return collect(this.paginatedLocations.data).map((location) => {
        Object.entries(location).forEach(([key, value]) => {
          location[key] = Array.isArray(value) ? collect(value) : value;
        });

        return location;
      });
    },
  },

  methods: {},
};
</script>
