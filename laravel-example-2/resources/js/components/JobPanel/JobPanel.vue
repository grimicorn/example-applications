<style>
  @import "./JobPanel.css";
</style>

<template>
  <panel :data-opened="dataOpened">
    <template slot="toggle">
      <slot name="toggle"></slot>
    </template>

    <template slot="header">
      <job-flag
        :data-job="job"
        :data-show-date="true"
      ></job-flag>
      <span class="job-id">#{{ job.work_order_number }}</span>
      <span class="customer-name">{{ job.customer_name }}</span>
      <input-select
        data-label="Machine"
        data-name="machine_id"
        v-model="job.machine_id"
        :data-options="machinesForSelect"
        :data-save-action="saveAction"
        data-save-method="PATCH"
      ></input-select>
    </template>

    <div class="tab-container">
      <button
        @click="switchToPanel(1)"
        :class="currentPanel === 1 ? 'active' : ''"
        class="tab"
      >
        Status
      </button>
      <button
        @click="switchToPanel(2)"
        :class="currentPanel === 2 ? 'active' : ''"
        class="tab"
      >
        Job Info
      </button>
      <button
        @click="switchToPanel(3)"
        :class="currentPanel === 3 ? 'active' : ''"
        class="tab"
      >
        Details
      </button>
    </div>
    <div class="tab-content-container">
      <div v-show="currentPanel === 1">
        <job-status v-model="job"></job-status>
      </div>
      <div v-show="currentPanel === 2">
        <job-info v-model="job"></job-info>
      </div>
      <div v-show="currentPanel === 3">
        <job-details v-model="job"></job-details>
      </div>
    </div>
  </panel>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  props: {
    value: {
      type: Object,
      required: true
    },

    dataOpened: {
      type: Boolean,
      default: false
    },

    dataCurrentPanel: {
      type: Number,
      default: 1
    }
  },

  data() {
    return {
      currentPanel: this.dataCurrentPanel,
      job: this.value
    };
  },

  computed: {
    saveAction() {
      return route("jobs.update", { job: this.job });
    },

    ...mapGetters(["machinesForSelect"])
  },

  methods: {
    switchToPanel(panel) {
      this.currentPanel = panel;
    },

    emitInput() {
      this.$emit("input", this.job);
    }
  },

  watch: {
    value(newValue) {
      this.job = newValue;
    },

    dataCurrentPanel(newValue) {
      this.currentPanel = newValue;
    },

    job(newValue) {
      this.$emit("input", job);
    }
  }
};
</script>
