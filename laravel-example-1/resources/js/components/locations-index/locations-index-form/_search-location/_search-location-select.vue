<template>
  <div>
    <button
      v-if="!display"
      type="button"
      class="button-link"
      title="Load Search Location"
      @click="display = true"
    >
      <icon data-name="add-solid" class="w-4 h-4"></icon>
      <span class="sr-only"></span>
    </button>

    <div v-if="display" class="flex items-center mr-2">
      <label for="select_search_location" class="sr-only">
        Load a search location.
      </label>
      <select
        placeholder="Load a search location."
        id="select_search_location"
        :value="selected"
        @input="handleSelectInput"
        class="mr-2"
      >
        <option value="" selected disabled>Select a search location.</option>
        <option
          :value="address"
          v-for="{ id, address, name } in searchLocations"
          :key="id"
          v-text="name"
        ></option>
      </select>
      <button type="button" title="Cancel" @click="display = false">
        <icon data-name="close" class="w-4 h-4 text-danger"></icon>
        <span class="sr-only">Cancel</span>
      </button>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  props: {},

  data() {
    return {
      selected: "",
      display: false,
    };
  },

  computed: {
    ...mapState(["searchLocations"]),
  },

  methods: {
    handleSelectInput($e) {
      this.selected = $e.target.value;
      this.$emit("input", this.selected);
      this.selected = "";
      this.display = false;
    },
  },
};
</script>
