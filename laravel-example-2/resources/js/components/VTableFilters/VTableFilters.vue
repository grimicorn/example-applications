<style>
  @import "./VTableFilters.css";
</style>
<template>
  <div class="flex flex-col lg:flex-row vtable-filters">
    <div class="border-2 border-gray-400 rounded-lg py-4 px-6 mb-6 lg:mb-0 lg:mr-6">
      <input-select
        data-label="Custom Preset"
        data-name="custom_preset"
        data-placeholder="Select a preset"
        :data-options="{
            'on_board': 'On Board',
        }"
        v-model="preset"
        :data-clearable="true"
      >
      </input-select>
    </div>

    <form
      @submit.prevent="$emit('filter')"
      class="border-2 border-gray-400 rounded-lg py-4 px-6 lg:flex items-center flex-1"
    >
      <div class="mr-4 lg:flex">
        <input-text
          data-name="work_order_number"
          v-model="workOrderNumber"
          data-label="Work Order #"
          data-placeholder="0000000000"
          class="lg:mr-6"
          :data-disabled="presetSelected"
        ></input-text>

        <input-text
          data-name="control_number"
          v-model="controlNumber"
          data-label="Control #"
          data-placeholder="0000000000"
          class="lg:mr-6"
          :data-disabled="presetSelected"
        ></input-text>

        <input-select
          data-label="Status"
          data-name="wip_status"
          data-placeholder="Select a status"
          v-model="wipStatus"
          :data-options="jobStatusesForSelect"
          class="lg:mr-6"
          :data-disabled="presetSelected"
          :data-clearable="true"
        >
        </input-select>

        <input-select
          data-label="Machine Id"
          data-name="machine_id"
          data-placeholder="Select a machine"
          v-model="machineId"
          :data-options="machinesForSelect"
          :data-disabled="presetSelected"
          :data-clearable="true"
        >
        </input-select>

      </div>

      <div class="flex-1 mt-auto">
        <button
          type="submit"
          class="button mt-6 lg:mt-0"
        >Filter</button>
      </div>
    </form>
  </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
import collect from "collect.js";

export default {
  props: {
    value: {
      type: Object,
      default() {
        return {
          workOrderNumber: "",
          controlNumber: "",
          wipStatus: "",
          machineId: ""
        };
      }
    }
  },

  data() {
    return {
      workOrderNumber: this.value.workOrderNumber,
      controlNumber: this.value.controlNumber,
      wipStatus: this.value.wipStatus,
      machineId: this.value.machineId,
      preset: ""
    };
  },

  computed: {
    filters() {
      if (this.presetSelected) {
        return this.presets[this.preset];
      }

      return {
        workOrderNumber: this.workOrderNumber,
        controlNumber: this.controlNumber,
        wipStatus: this.wipStatus ? [this.wipStatus] : [],
        machineId: this.machineId ? [this.machineId] : []
      };
    },

    presetSelected() {
      if (!this.preset) {
        return false;
      }

      return typeof this.presets[this.preset] !== "undefined";
    },

    presets() {
      return {
        on_board: {
          workOrderNumber: "",
          controlNumber: "",
          wipStatus: ["J", "K"],
          machineId: collect(this.machines)
            .where("is_on_board", true)
            .pluck("id")
            .toArray()
        }
      };
    },

    ...mapGetters(["machinesForSelect", "jobStatusesForSelect"]),
    ...mapState(["machines"])
  },

  methods: {},

  mounted() {},

  watch: {
    filters(value) {
      this.$emit("input", value);
    },

    presetSelected(value) {
      if (!value) {
        return;
      }

      this.workOrderNumber = "";
      this.controlNumber = "";
      this.wipStatus = "";
      this.machineId = "";
    }
  }
};
</script>
