<style>
  @import "./VTable.css";
</style>
<template>
  <div>
    <v-table-filters
      v-model="filters"
      @filter="update"
    ></v-table-filters>

    <table
      class="v-table w-full mt-6 border-2 border-gray-400 rounded-lg border-separate"
    >
      <thead>
        <tr
          class="bg-gray-400 text-white rounded-tl-lg rounded-tr-lg"
        >
          <th
            v-for="(header, index) in headers"
            :key="index"
            :class="header.sorted ? 'active-sort' : ''"
          >
            <button
              type="button"
              v-if="header.sortable"
              @click="sort(header.attribute)"
              class="flex items-center"
            >
              <slot
                name="header"
                :header="header"
              >
                {{ header.label }}
                <v-table-sort-icon
                  :data-sort-asc="sortAsc"
                  :data-is-sorted="header.attribute === sortAttribute"
                ></v-table-sort-icon>
              </slot>
            </button>
            <slot
              v-else
              name="header"
              :header="header"
            >
              {{ header.label }}
            </slot>
          </th>
        </tr>
      </thead>

      <tbody v-if="!updating">
        <tr v-if="noResults">
          <td
            :colspan="headers.length"
            class="no-results"
          >
            <slot name="no-results">
              No results found for current filters.
            </slot>
          </td>
        </tr>
        <tr
          v-for="(item, index) in items"
          :key="index"
        >
          <slot
            name="row"
            :item="item"
            :index="index"
            :update="update"
          ></slot>
        </tr>
      </tbody>
    </table>

    <pagination
      :data-paginator="paginatedItems"
      @changed="handlePageChanged"
    ></pagination>
  </div>
</template>

<script>
import queryParameters from "@utilities/query-parameters";
import collect from "collect.js";
import { mapMutations } from "vuex";

export default {
  props: {
    dataHeaders: {
      required: true,
      type: Array
    },

    dataPaginatedItems: {
      required: true,
      type: Object
    },

    dataSortAttribute: {
      type: String,
      default: queryParameters.get("sort")
        ? queryParameters.get("sort").replace(/^-+/gm, "")
        : undefined
    },

    dataSortAsc: {
      type: Boolean,
      default: queryParameters.get("sort")
        ? queryParameters.get("sort").indexOf("-") !== 0
        : true
    },

    dataBaseUrl: {
      type: String,
      default: window.location.href.replace(/[\?#].+/gm, "")
    }
  },

  data() {
    return {
      paginatedItems: this.dataPaginatedItems,
      currentPage: this.dataPaginatedItems.current_page,
      sortAttribute: this.dataSortAttribute,
      sortAsc: this.dataSortAsc,
      errorMessage: "",
      updating: false,
      filters: {
        workOrderNumber: queryParameters.get("filter[work_order_number]"),
        controlNumber: queryParameters.get("filter[control_number]"),
        wipStatus: queryParameters.get("filter[wip_status]"),
        machineId: queryParameters.get("filter[machine_id]")
      }
    };
  },

  computed: {
    errorAlert() {
      return {
        dismissible: false,
        type: "danger",
        timeout: 0,
        message: this.errorMessage
      };
    },

    baseUrl() {
      return this.dataBaseUrl.replace(/[\?#].+/gm, "");
    },

    items() {
      return this.paginatedItems.data ? this.paginatedItems.data : [];
    },

    headers() {
      return collect(this.dataHeaders).map((header) => {
        header.sorted = header.attribute == this.sortAttribute;
        return header;
      }).all();
    },

    url() {
      let parameters = collect(this.parameters)
        .filter()
        .map((value, key) => `${key}=${value}`)
        .toArray()
        .join("&");

      let url = this.baseUrl;
      if (parameters) {
        return `${url}?${parameters}`;
      }

      return url;
    },

    noResults() {
      return this.items.length === 0;
    },

    parameters() {
      return {
        "filter[work_order_number]": this.filters.workOrderNumber,
        "filter[control_number]": this.filters.controlNumber,
        "filter[wip_status]": this.filters.wipStatus,
        "filter[machine_id]": this.filters.machineId,
        sort: this.sortAsc ? this.sortAttribute : `-${this.sortAttribute}`,
        page: this.currentPage
      };
    }
  },

  methods: {
    handlePageChanged(page) {
      this.currentPage = page;
    },

    sort(attribute) {
      if (attribute === this.sortAttribute) {
        this.sortAsc = !this.sortAsc;
      } else {
        this.sortAttribute = attribute;
        this.sortAsc = true;
      }

      this.$emit("sorted", {
        asc: this.sortAsc,
        attribute: this.sortAttribute
      });

      this.update();
    },

    update() {
      history.pushState({}, document.title, this.url);
      this.updating = true;
      this.page = 1;

      this.$http
        .get(this.url)
        .then(({ data: { data: { items } } }) => {
          this.removeAlert(this.errorAlert);
          this.errorMessage = "";
          this.paginatedItems = items;
          this.updating = false;
        })
        .catch(({ response: { status, statusText } }) => {
          this.errorMessage = "Something went wrong please try again.";
          this.addAlert(this.errorAlert);
          this.updating = false;
          console.error(`${status} ${statusText}`);
        });
    },

    ...mapMutations(["addAlert", "removeAlert"])
  },

  watch: {
    dataPaginatedItems(value) {
      this.paginatedItems = value;
    },

    dataSortAttribute(value) {
      this.sortAttribute = value;
    },

    dataSortAsc(value) {
      this.sortAsc = !!value;
    }
  }
};
</script>
