<template>
  <div>
    <button
      v-if="!display"
      type="button"
      class="button-link"
      title="Load Search Location"
      @click="display = true"
    >
      <icon data-name="trash" class="w-4 h-4"></icon>
      <span class="sr-only"></span>
    </button>

    <div v-if="display" class="flex items-center mr-2">
      <label for="delete_search_location" class="sr-only">
        Select search location to delete.
      </label>
      <select
        placeholder="Load a search location."
        id="delete_search_location"
        v-model="selected"
        class="mr-2"
        :disabled="deleting"
      >
        <option value="" selected>Select search location to delete.</option>
        <option
          :value="id"
          v-for="{ id, name } in searchLocations"
          :key="id"
          v-text="name"
        ></option>
      </select>

      <button
        type="button"
        title="Add"
        @click="handleDelete"
        class="mr-2"
        :disabled="deleting"
        v-if="selected"
      >
        <icon data-name="checkmark" class="w-4 h-4 text-success"></icon>
        <span class="sr-only">Add</span>
      </button>

      <button
        type="button"
        title="Cancel"
        @click="display = false"
        :disabled="deleting"
      >
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
      deleting: false,
      selected: "",
      display: false,
    };
  },

  computed: {
    ...mapState(["searchLocations"]),
  },

  methods: {
    handleDelete() {
      if (!this.selected) {
        return;
      }

      this.deleting = true;
      const url = this.$route("search-locations.destroy", {
        id: this.selected,
      }).toString();

      this.$http
        .post(url, { _method: "delete" })
        .then(() => {
          this.deleting = false;
          this.display = false;
          this.selected = "";
        })
        .catch(() => {
          this.deleting = false;
        });
    },
  },
};
</script>
