<style>
  @import "./JobInfo.css";
</style>

<template>
  <div class="job-info">
    <div class="container">
      <span class="label">Customer Name</span>
      <span v-text="job.customer_name"></span>
    </div>
    <div class="container">
      <span class="label">SKU</span>
      <span v-text="job.sku_number"></span>
    </div>
    <div class="container">
      <span class="label">WO Start Date</span>
      <v-date
        data-output-format="MM/DD/YYYY"
        :data-date="job.start_at"
      ></v-date>
    </div>
    <div class="container">
      <inline-editable data-label="WO Due Date">
        <template slot="value">
          <v-date
            data-output-format="MM/DD/YYYY"
            :data-date="job.due_at"
          ></v-date>
        </template>

        <template slot="input">
          <input-text
            class="input-hide-label"
            data-name="due_at"
            data-type="date"
            data-label="WO Due Date"
            :value="job.due_at"
            :data-save-action="saveAction"
            data-save-method="PATCH"
            @save:success="handleDueAtSaveSuccess"
          ></input-text>
        </template>
      </inline-editable>
    </div>

    <div class="container">
      <inline-editable data-label="Priority">
        <template slot="value">
          <span v-text="job.priority"></span>
        </template>

        <template slot="input">
          <input-text
            class="input-hide-label"
            data-name="priority"
            data-type="number"
            data-label="Priority"
            data-min="0"
            data-step="1"
            :value="job.priority"
            :data-save-action="saveAction"
            data-save-method="PATCH"
            @input="emitInput"
          ></input-text>
        </template>
      </inline-editable>
    </div>

    <div class="container job-sizes">
      <div class="job-size">
        <span class="label">S</span>
        <span v-text="job.small_quantity"></span>
      </div>
      <div class="job-size">
        <span class="label">M</span>
        <span v-text="job.medium_quantity"></span>
      </div>
      <div class="job-size">
        <span class="label">L</span>
        <span v-text="job.large_quantity"></span>
      </div>
      <div class="job-size">
        <span class="label">XL</span>
        <span v-text="job.xlarge_quantity"></span>
      </div>
      <div class="job-size">
        <span class="label">2XL</span>
        <span v-text="job['2xlarge_quantity']"></span>
      </div>
      <div class="job-size">
        <span class="label">Other</span>
        <span v-text="job.other_quantity"></span>
      </div>
    </div>

    <div class="container">
      <span class="label">Total Quantity</span>
      <span v-text="job.total_quantity"></span>

      <span class="label mt-8">Control #</span>
      <span v-text="job.control_numbers_label"></span>
    </div>

    <div class="container">
      <span class="label">Impressions</span>
      <span v-text="job.impressions_count"></span>
    </div>

    <div class="container">
      <span class="label">Screens</span>
      <span v-text="job.screens_count"></span>
    </div>

    <div class="container">
      <span
        class="label"
        v-if="job.imported_placement_1"
      >Placement 1</span>
      <span
        v-text="job.imported_placement_1"
        v-if="job.imported_placement_1"
        class="block mb-8"
      ></span>

      <span
        class="label"
        v-if="job.imported_placement_2"
      >Placement 2</span>
      <span
        v-text="job.imported_placement_2"
        v-if="job.imported_placement_2"
        class="block mb-8"
      ></span>

      <span
        class="label"
        v-if="job.imported_placement_3"
      >Placement 3</span>
      <span
        v-text="job.imported_placement_3"
        v-if="job.imported_placement_3"
        class="block mb-8"
      ></span>

      <span
        class="label"
        v-if="job.imported_placement_4"
      >Placement 4</span>
      <span
        v-text="job.imported_placement_4"
        v-if="job.imported_placement_4"
        class="block mb-8"
      ></span>
    </div>

    <div class="container">
      <inline-editable data-label="Pick Status">
        <template slot="value">
          <span v-text="job.pick_status"></span>
        </template>

        <template slot="input">
          <input-select
            class="input-hide-label"
            data-label="Pick Status"
            data-name="pick_status"
            v-model="job.pick_status"
            :data-options="pickStatusesForSelect"
            :data-save-action="saveAction"
            data-save-method="PATCH"
            @input="emitInput"
          >
          </input-select>
        </template>
      </inline-editable>
    </div>
    <div class="container">
      <span class="label">Product Location</span>
      <span v-text="job.product_location_wc"></span>
    </div>
    <div class="container">
      <inline-editable data-label="Art Status">
        <template slot="value">
          <span v-text="job.art_status"></span>
        </template>

        <template slot="input">
          <input-select
            class="input-hide-label"
            data-label="Art Status"
            data-name="art_status"
            v-model="job.art_status"
            :data-options="artStatusesForSelect"
            :data-save-action="saveAction"
            data-save-method="PATCH"
            @input="emitInput"
          >
          </input-select>
        </template>
      </inline-editable>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

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
    },

    ...mapGetters(["pickStatusesForSelect", "artStatusesForSelect"])
  },

  methods: {
    emitInput() {
      this.$emit("input", this.job);
    },

    handleDueAtSaveSuccess({ data: { model } }) {
      this.job.due_at = model.due_at;
    }
  },

  watch: {
    value(newValue) {
      this.job = newValue;
    }
  }
};
</script>
