<style scoped>
.map {
  height: 75vh;
}
</style>

<template>
  <div id="map" class="map js-map" ref="map"></div>
</template>

<script>
import initalizeMap from "@/libs/google/map";
import { mapState } from "vuex";

export default {
  props: {},

  data() {
    return {};
  },

  computed: {
    ...mapState(["user", "paginatedLocations", "centerCoordinates"]),
  },

  mounted() {
    this.initalizeMap();
  },

  watch: {
    paginatedLocations() {
      this.initalizeMap();
    },
  },

  methods: {
    initalizeMap() {
      this.$nextTick(() => {
        initalizeMap({
          $map: this.$refs["map"],
          usersCurrentLocation: this.user.currentLocation,
          locations: this.paginatedLocations.data,
          centerCoordinates: this.centerCoordinates,
        });
      });
    },
  },
};
</script>
