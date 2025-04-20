<template>
  <div class="status">
    <div class="container">
      <input-select
        data-name="flag"
        data-label="Day of Week Due"
        v-model="job.flag"
        :data-options="jobFlagsForSelect"
        :data-save-action="saveAction"
        data-save-method="PATCH"
        @input="emitInput"
      >
      </input-select>
      <input-select
        data-name="wip_status"
        data-label="Status"
        :value="job.wip_status"
        :data-options="jobStatusesForSelect"
        :data-save-action="saveAction"
        data-save-method="PATCH"
        @input="handleJobStatusInput"
      >
      </input-select>

      <div v-if="isJStatus">
        <input-checkbox
          data-name="garment_ready"
          data-icon-name="tshirt"
          data-label="Garment Ready"
          v-model="job.garment_ready"
          :data-save-action="saveAction"
          data-save-method="PATCH"
          @input="emitInput"
        ></input-checkbox>

        <input-checkbox
          data-name="screens_ready"
          v-model="job.screens_ready"
          data-icon-name="chess-board"
          data-label="Screens Ready"
          :data-save-action="saveAction"
          data-save-method="PATCH"
          @input="emitInput"
        ></input-checkbox>
        <input-checkbox
          data-name="ink_ready"
          v-model="job.ink_ready"
          data-icon-name="tint"
          data-label="Ink Ready"
          :data-save-action="saveAction"
          data-save-method="PATCH"
          @input="emitInput"
        ></input-checkbox>

      </div>
    </div>

  </div>
</template>

<script>
// ============================================================
// IMPORTANT: When adding inputs
/// use v-mode="job.property" and @input="emitInput"
// ============================================================
import { mapGetters, mapState } from "vuex";
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
    currentStatus() {
      return this.job.wip_status;
    },

    isJStatus() {
      return this.currentStatus.toString() === this.wipStatuses["J"];
    },

    saveAction() {
      return route("jobs.update", { job: this.job });
    },

    ...mapState(["wipStatuses"]),
    ...mapGetters(["jobFlagsForSelect", "jobStatusesForSelect"])
  },

  methods: {
    handleJobStatusInput(value) {
      this.job.wip_status = value;
      this.job.is_ready = this.job.wip_status === this.wipStatuses["K"];
      this.emitInput();
    },

    emitInput() {
      this.$emit("input", this.job);
    }
  },

  watch: {
    value(newValue) {
      this.job = newValue;
    }
  }
};
</script>
