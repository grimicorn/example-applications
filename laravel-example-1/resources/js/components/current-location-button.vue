<template>
  <div>
    <button
      v-show="supported"
      :disabled="loading"
      type="button"
      class="block w-5 h-5 ml-2"
      :class="{
        'text-gray-500': loading,
      }"
      @click="getLocation"
      :title="
        error
          ? 'You\'ve previously blocked location'
          : 'Click to set your location.'
      "
      v-if="!errorMessage"
    >
      <icon
        :data-name="loading ? 'cog' : 'location-current'"
        :data-animation="loading ? 'spin' : undefined"
      ></icon>
      <span class="sr-only">Use your current loation</span>
    </button>

    <span
      v-text="errorMessage"
      v-if="errorMessage"
      class="block ml-2 text-danger"
    ></span>
  </div>
</template>

<script>
export default {
  props: {},

  data() {
    return {
      error: false,
      loading: false,
      supported: typeof navigator.geolocation !== "undefined",
    };
  },

  computed: {
    errorMessage() {
      if (this.error) {
        return "Could not get location";
      }
    },
  },

  methods: {
    storeUserLocation(coordinates) {
      this.loading = true;

      this.$http
        .post(this.$route("user-location.store"), coordinates)
        .then((response) => {
          this.$store.commit("setUserLocation", {
            latitude: coordinates.latitude,
            longitude: coordinates.longitude,
            address: response.data.data.address,
          });

          this.$store.commit("updateLocationsIndexFormField", {
            name: "address",
            value: response.data.data.address,
          });

          this.loading = false;
        })
        .catch(() => {
          this.error = true;
          this.loading = false;
        });
    },

    getLocation() {
      this.loading = true;
      navigator.geolocation.getCurrentPosition(
        (position) => {
          this.storeUserLocation({
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
          });
        },
        () => {
          this.error = true;
          this.loading = false;
        }
      );
    },
  },
};
</script>
