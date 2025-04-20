<style>
  @import "./JobCard.css";
</style>

<template>
  <job-panel
    v-model="job"
    class="job-card"
  >
    <template slot="toggle">
      <div>
        <div class="job-card-header">
          <job-flag :data-job="job">
          </job-flag>
          <span v-text="`#${job.work_order_number}`"></span>
        </div>

        <div class="info-section border-b">
          <div
            v-text="job.customer_name"
            class="border-r flex-1"
          ></div>
          <div class="text-center">
            <v-date
              data-output-format="MM/DD"
              :data-date="job.due_at"
            ></v-date>
          </div>
        </div>

        <div class="info-section border-b">
          <div class="p-2">
            <span class="label">IMP</span>
            <span
              class="info"
              v-text="job.impressions_count"
            ></span>
          </div>
          <div class="p-2">
            <span class="label">QTY</span>
            <span
              v-text="job.total_quantity"
              class="info"
            ></span>
          </div>
        </div>

        <div class="info-section border-b">
          <div class="p-2">
            <span class="label">FRT</span>
            <span
              class="info"
              v-text="job.front_screen_count"
            ></span>
          </div>
          <div class="p-2">
            <span class="label">BCK</span>
            <span
              class="info"
              v-text="job.back_screen_count"
            ></span>
          </div>
          <div class="p-2">
            <span class="label">OTH</span>
            <span
              class="info"
              v-text="job.other_screen_count"
            >{OTH}</span>
          </div>
        </div>

        <div class="info-section icons text-gray-300">
          <span
            class="status-icon"
            :class="{'text-gray-400' : job.garment_ready}"
          >
            <icon data-name="tshirt"></icon>
          </span>
          <span
            class="status-icon"
            :class="{'text-gray-400' : job.screens_ready}"
          >
            <icon data-name="chess-board"></icon>
          </span>
          <span
            class="status-icon"
            :class="{'text-gray-400' : job.ink_ready}"
          >
            <icon data-name="tint"></icon>
          </span>
          <span
            class="status-icon"
            :class="{'text-gray-400' : job.is_ready}"
          >
            <icon data-name="check"></icon>
          </span>
        </div>
      </div>
    </template>
  </job-panel>
</template>

<script>
export default {
  props: {
    dataJob: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      job: this.dataJob
    };
  },

  computed: {},

  methods: {},

  watch: {
    dataJob(newValue) {
      this.job = newValue;
    }
  }
};
</script>
