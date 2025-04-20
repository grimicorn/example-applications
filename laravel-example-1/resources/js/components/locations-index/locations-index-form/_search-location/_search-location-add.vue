<template>
  <div v-if="address">
    <button
      type="button"
      class="flex items-center button-link"
      title="Add current address as a Search Location"
      @click="display = true"
      v-if="!display"
    >
      <icon data-name="save" class="w-4 h-4"></icon>
      <span class="sr-only">Add current address as a Search Location</span>
    </button>

    <div v-if="display">
      <label for="search_location_name" class="sr-only">
        Search Location Name
      </label>
      <input
        type="text"
        id="search_location_name"
        placeholder="Name"
        v-model="name"
        ref="search_location_name"
        class="mr-2"
        :disabled="adding"
        :class="{
          'border-danger text-danger': !!this.errors.name,
        }"
        @input="clearError('name')"
      />
      <label for="search_location_address" class="sr-only">
        Search Location Address
      </label>
      <input
        type="text"
        id="search_location_address"
        readonly
        :value="address"
        class="mr-2"
        :disabled="adding"
        :class="{
          'border-danger text-danger': !!this.errors.address,
        }"
        @input="clearError('address')"
      />
      <button
        type="button"
        title="Add"
        @click="handleAdd"
        class="mr-2"
        :disabled="adding"
      >
        <icon data-name="checkmark" class="w-4 h-4 text-success"></icon>
        <span class="sr-only">Add</span>
      </button>

      <button
        type="button"
        title="Cancel"
        @click="display = false"
        class="mr-2"
        :disabled="adding"
      >
        <icon data-name="close" class="w-4 h-4 text-danger"></icon>
        <span class="sr-only">Cancel</span>
      </button>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import collect from "collect.js";

export default {
  props: {
    dataAddress: {
      type: String,
    },
  },

  data() {
    return {
      name: this.dataAddress,
      display: false,
      adding: false,
      errors: {},
    };
  },

  computed: {
    ...mapState(["searchLocations"]),
    address() {
      const addresses = collect(this.searchLocations).map(
        (location) => location.address
      );

      if (addresses.contains(this.dataAddress)) {
        return;
      }

      return this.dataAddress;
    },
  },

  methods: {
    handleAdd() {
      this.adding = true;
      this.$http
        .post(this.$route("search-locations.store"), {
          name: this.name,
          address: this.address,
        })
        .then((response) => {
          this.adding = false;
          this.display = false;
          this.errors = {};
          this.$store.commit("addSearchLocation", response.data.location);
        })
        .catch((error) => {
          this.adding = false;
          if (error.response && error.response.status === 422) {
            this.errors = error.response.data.errors;
          }
        });
    },

    clearError(key) {
      if (this.errors[key]) {
        delete this.errors[key];
      }
    },
  },

  watch: {
    display(value) {
      this.name = this.address;

      if (value) {
        this.$nextTick(() => {
          this.$refs.search_location_name.select();
          this.$refs.search_location_name.focus();
        });
      }
    },
  },
};
</script>
