<style scoped lang="css">
/* Styles are in app.css. Upgrading to Laravel Mix v6 broke @apply in vue SFC :( */
</style>
<template>
  <div
    v-if="!editing"
    class="flex items-center py-1 mr-2 leading-6 border-b-2 border-transparent location-inline-edit-wrapper"
  >
    <!-- Value -->
    <slot name="value" :value="currentValue">
      {{ currentValue }}
    </slot>

    <!-- Edit -->
    <button
      type="button"
      @click="edit"
      title="Edit"
      class="p-2 ml-1 text-primary-600"
    >
      <span class="sr-only">Edit</span>
      <icon data-name="edit-pencil" class="w-4 h-4"></icon>
    </button>
  </div>

  <form v-else @submit.prevent="save">
    <div class="flex items-center">
      <!-- Editor -->
      <slot name="label" id="inputId" label="inputLabel">
        <label :for="inputId" v-text="inputLabel" class="sr-only"></label>
      </slot>

      <div
        :class="{ 'opacity-50': saving, 'has-error': !!errorMessage }"
        class="flex-1"
      >
        <slot
          name="input"
          :inputId="inputId"
          :currentValue="currentValue"
          :inputName="dataName"
          :saving="saving"
          :setCurrentValue="setCurrentValue"
          :label="inputLabel"
        >
        </slot>
      </div>

      <!-- Save -->
      <button
        type="submit"
        title="Save"
        :disabled="saving"
        class="p-2 mr-1 text-success"
      >
        <span class="sr-only">Save</span>
        <icon
          :data-name="saving ? 'cog' : 'checkmark'"
          class="w-3 h-3"
          :data-animation="saving ? 'spin' : undefined"
        ></icon>
      </button>

      <!-- Cancel -->
      <button
        type="button"
        @click="cancel"
        title="Cancel"
        v-show="!saving"
        :disabled="saving"
        class="p-2 mr-1 text-danger"
      >
        <span class="sr-only">Cancel</span>
        <icon data-name="close" class="w-3 h-3"></icon>
      </button>
    </div>
    <span
      v-if="errorMessage"
      class="block pt-2 text-sm font-bold text-danger"
      v-text="errorMessage"
    ></span>
  </form>
</template>

<script>
export default {
  props: {
    value: {
      required: true,
    },
    dataName: {
      required: true,
      type: String,
    },
    dataLabel: {
      type: String,
    },
    dataLocation: {
      required: true,
      type: Object,
    },
  },

  data() {
    return {
      editing: false,
      saving: false,
      currentValue: this.value,
      previousValue: this.value,
      errorMessage: undefined,
    };
  },

  computed: {
    inputLabel() {
      const label = this.dataLabel ? this.dataLabel : this.dataName;
      return `Edit ${label}`;
    },

    inputId() {
      return `location_${this.dataLocation.id}_${this.dataName}`;
    },
  },

  methods: {
    edit() {
      this.editing = true;
      this.previousValue = this.currentValue;
    },

    save() {
      this.saving = true;
      const url = this.$route("locations.update", {
        location: this.dataLocation.slug,
      });

      this.$http
        .post(url.toString(), {
          _method: "patch",
          [this.dataName]: this.currentValue,
        })
        .then((response) => {
          this.editing = false;
          this.saving = false;
          this.previousValue = this.currentValue;
          this.$emit("input", this.currentValue);
        })
        .catch((error) => {
          this.saving = false;

          if (error.response && error.response.status === 422) {
            const inputError = error.response.data.errors[this.dataName] ?? {};
            this.errorMessage = inputError[0] ?? error.response.data.message;
            return;
          }

          this.errorMessage = "Something went wrong please try again.";
        });
    },

    cancel() {
      this.editing = false;
      this.currentValue = this.previousValue;
    },

    setCurrentValue(value) {
      this.currentValue = value;
    },
  },

  watch: {
    value(value) {
      this.currentValue = value;
    },

    currentValue() {
      this.errorMessage = undefined;
    },
  },
};
</script>
