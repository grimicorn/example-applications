<template>
  <v-page>
    <v-table
      :data-headers="[
          {
              label: 'Work Order #',
              sortable: true,
              attribute: 'work_order_number'
          },
          {
              label: 'Control #1',
              sortable: false
          },
          {
              label: 'Customer',
              sortable: true,
              attribute: 'customer_name'
          },
          {
              label: 'Due Date',
              sortable: true,
              attribute: 'due_at'
          },
          {
              label: 'Day of Week Due',
              sortable: true,
              attribute: 'flag'
          },
          {
              label: 'Status',
              sortable: true,
              attribute: 'wip_status'
          },
          {
              label: 'Machine ID',
              sortable: true,
              attribute: 'machine_id'
          },
      ]"
      :data-paginated-items="dataPaginatedJobs"
      data-sort-attribute="due_at"
      :data-sort-asc="false"
    >
      <template v-slot:row="{item: job, index}">
        <td>
          <job-panel :value="job">
            <template slot="toggle">
              {{ job.work_order_number }}
            </template>
          </job-panel>
        </td>
        <td v-text="job.control_numbers_label"></td>
        <td v-text="job.customer_name"></td>
        <td v-text="job.due_at"></td>
        <td>
          <input-select
            class="input-hide-label"
            data-label="Day of Week Due"
            data-name="flag"
            :data-id="`flag_${index}`"
            v-model="job.flag"
            :data-options="jobFlagsForSelect"
            :data-save-action="saveAction(job)"
            data-save-method="put"
          ></input-select>
        </td>
        <td v-text="job.wip_status"></td>
        <td>
          <input-select
            class="input-hide-label"
            data-label="Machine Id"
            data-name="machine_id"
            :data-id="`machine_id_${index}`"
            v-model="job.machine_id"
            :data-options="machinesForSelect"
            :data-save-action="saveAction(job)"
            data-save-method="put"
          >
          </input-select>
        </td>
      </template>
    </v-table>
  </v-page>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  props: {
    dataPaginatedJobs: {
      required: true,
      type: Object
    }
  },

  data() {
    return {};
  },

  computed: {
    ...mapGetters(["machinesForSelect", "jobFlagsForSelect"])
  },

  methods: {
    saveAction(job) {
      return route("jobs.update", { job });
    }
  }
};
</script>
