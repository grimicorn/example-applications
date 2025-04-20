<template>
  <div>
    <locations-index-form></locations-index-form>

    <div class="flex items-center justify-between mb-6" v-if="showPagination">
      <pagination-details
        :dataPaginator="paginatedLocations"
      ></pagination-details>
      <global-drawer-toggle></global-drawer-toggle>
    </div>

    <alert
      data-type="loading"
      v-if="locationsLoading"
      :data-dismissible="false"
    >
      Loading Locations
    </alert>

    <alert data-type="warning" v-if="showEmptyNotice" :data-dismissible="false">
      No locations found.
    </alert>

    <locations-map v-if="showMap" class="mb-6"></locations-map>

    <locations-index-list
      v-if="showLocationsList"
      class="mb-6"
    ></locations-index-list>

    <pagination
      :dataPaginator="paginatedLocations"
      v-if="showPagination"
    ></pagination>
  </div>
</template>

<script>
import { mapState } from "vuex";
import GlobalDrawerToggle from "./locations-index-list/_locations-index-global-drawer-toggle";

export default {
  components: {
    GlobalDrawerToggle,
  },

  props: {},

  data() {
    return {};
  },

  computed: {
    ...mapState([
      "locationsIndexForm",
      "paginatedLocations",
      "locationsLoading",
    ]),

    showMap() {
      const showMap = this.locationsIndexForm.map.toString() === "1";
      return !this.showEmptyNotice && !this.locationsLoading && showMap;
    },

    showEmptyNotice() {
      const hasLocations =
        this.paginatedLocations && this.paginatedLocations.total > 0;
      return !hasLocations && !this.locationsLoading;
    },

    showLocationsList() {
      return !this.showEmptyNotice && !this.locationsLoading && !this.showMap;
    },

    showPagination() {
      return !this.locationsLoading && !this.showEmptyNotice;
    },
  },

  methods: {},
};
</script>
