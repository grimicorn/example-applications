<template>
  <div class="job-details">
    <div class="container job-artwork-upload">
      <input-file
        :data-save-action="saveAction"
        data-save-method="PATCH"
        data-name="print_detail"
        data-label="Artwork"
        :data-current-file="job.print_detail"
        @save:success="handlePrintDetailSaveSucess"
      ></input-file>
    </div>

    <div class="container">
      <input-textarea
        :data-save-action="saveAction"
        data-save-method="PATCH"
        data-name="notes"
        data-label="Notes"
        v-model="job.notes"
        @input="emitInput"
      >
        <template v-slot:save-button>
          Save
        </template>
      </input-textarea>
    </div>
  </div>
</template>

<script>
// ============================================================
// IMPORTANT: When adding inputs
/// use v-mode="job.property" and @input="emitInput"
// ============================================================
export default {
  props: {
    value: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      job: this.value
    };
  },

  computed: {
    saveAction() {
      return route("jobs.update", { job: this.job });
    }
  },

  methods: {
    emitInput() {
      this.$emit("input", this.job);
    },

    handlePrintDetailSaveSucess(value) {
      this.job.print_detail = value;
      this.emitInput();
    }
  },

  watch: {
    value(newValue) {
      this.job = newValue;
    },

    value(newValue) {
      this.job = newValue;
    }
  }
};
</script>
