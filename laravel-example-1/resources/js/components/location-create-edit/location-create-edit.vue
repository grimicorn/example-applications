<template>
  <form
    method="POST"
    :action="action"
    class="px-4 py-4 mb-8 bg-white rounded shadow"
    ref="form"
  >
    <!-- CSRF Token -->
    <input type="hidden" name="_token" :value="crsfToken" />

    <!-- Method -->
    <input type="hidden" name="_method" :value="method" v-if="method" />

    <!-- Name -->
    <div class="mb-6">
      <div class="flex items-center">
        <label
          for="name"
          class="mr-2"
          :class="{ 'text-danger': errors.has('name') }"
          >Name</label
        >
        <input
          type="text"
          name="name"
          id="name"
          class="flex-1"
          :class="{ 'border-danger': errors.has('name') }"
          :value="old.get('name', location.name)"
        />
      </div>
      <input-error data-key="name"></input-error>
    </div>

    <!-- Address -->
    <div class="mb-6">
      <div class="flex items-center">
        <label
          for="address"
          class="mr-2"
          :class="{ 'text-danger': errors.has('address') }"
          >Address</label
        >
        <location-autocomplete
          name="address"
          id="address"
          class="flex-1"
          :value="old.get('address', location.address)"
        ></location-autocomplete>
      </div>
      <input-error data-key="address"></input-error>
    </div>

    <!-- Best Time Of Year To Visit -->
    <div class="mb-6">
      <div class="flex items-center md:w-1/2">
        <label
          for="best_time_of_year_to_visit"
          class="mr-2 md:whitespace-nowrap"
          :class="{ 'text-danger': errors.has('best_time_of_year_to_visit') }"
        >
          Best Time Of Year To Visit
        </label>
        <input-fancy-select
          dataPlaceholder="Any"
          dataName="best_time_of_year_to_visit"
          dataId="best_time_of_year_to_visit"
          :value="
            old.get(
              'best_time_of_year_to_visit',
              location.best_time_of_year_to_visit
            )
          "
          :dataOptions="timesOfYearToVisitOptions"
        ></input-fancy-select>
      </div>
      <input-error data-key="best_time_of_year_to_visit"></input-error>
    </div>

    <!-- Best Time Of Day To Visit -->
    <div class="mb-6">
      <div class="flex items-center md:w-1/2">
        <label
          for="best_time_of_day_to_visit"
          class="mr-2 md:whitespace-nowrap"
          :class="{ 'text-danger': errors.has('best_time_of_day_to_visit') }"
        >
          Best Time Of Day To Visit
        </label>
        <input-fancy-select
          dataPlaceholder="Any"
          dataName="best_time_of_day_to_visit"
          dataId="best_time_of_day_to_visit"
          :value="
            old.get(
              'best_time_of_day_to_visit',
              location.best_time_of_day_to_visit
            )
          "
          :dataOptions="timesOfDayToVisitOptions"
        ></input-fancy-select>
      </div>
      <input-error data-key="best_time_of_day_to_visit"></input-error>
    </div>

    <!-- Rating -->
    <div class="mb-6">
      <div class="flex items-center">
        <label
          for="rating"
          class="mr-2"
          :class="{ 'text-danger': errors.has('rating') }"
        >
          Rating
        </label>
        <input
          type="number"
          min="1"
          max="5"
          step="1"
          name="rating"
          id="rating"
          :value="old.get('rating', location.rating)"
        />
      </div>
      <input-error data-key="rating"></input-error>
    </div>

    <!-- Visited -->
    <div class="mb-6">
      <div class="flex items-center">
        <label
          for="visited"
          class="mr-2"
          :class="{ 'text-danger': errors.has('visited') }"
        >
          Visited
        </label>

        <select name="visited" id="visited">
          <option
            value="0"
            :selected="old.get('visited', location.visited) == 0"
          >
            No
          </option>

          <option
            value="1"
            :selected="old.get('visited', location.visited) == 1"
          >
            Yes
          </option>
        </select>
      </div>
      <input-error data-key="visited"></input-error>
    </div>

    <!-- Access Difficulty -->
    <div class="w-full mb-6">
      <div class="flex items-center md:w-1/2">
        <label
          for="visited"
          class="mr-2 md:whitespace-nowrap"
          :class="{ 'text-danger': errors.has('visited') }"
        >
          Access Difficulty
        </label>
        <input-fancy-select
          dataPlaceholder="Unknown"
          dataName="access_difficulty"
          dataId="access_difficulty"
          :value="old.get('access_difficulty', location.access_difficulty)"
          :dataOptions="locationAccessDifficulties.toArray()"
        ></input-fancy-select>
      </div>

      <input-error data-key="access_difficulty"></input-error>
    </div>

    <!-- Traffic Level -->
    <div class="mb-6">
      <div class="flex items-center md:w-1/2">
        <label
          for="traffic_level"
          class="mr-2 md:whitespace-nowrap"
          :class="{ 'text-danger': errors.has('visited') }"
        >
          Traffic Level
        </label>
        <input-fancy-select
          dataPlaceholder="Unknown"
          dataName="traffic_level"
          dataId="traffic_level"
          :value="old.get('traffic_level', location.traffic_level)"
          :dataOptions="locationTrafficLevels.toArray()"
        ></input-fancy-select>
      </div>

      <input-error data-key="traffic_level"></input-error>
    </div>

    <!-- Walk Distance -->
    <div class="mb-6">
      <label
        for="walk_distance"
        class="mr-2"
        :class="{ 'text-danger': errors.has('visited') }"
      >
        Walk Distance (Miles)
      </label>

      <input
        type="number"
        min="0"
        step=".01"
        id="walk_distance"
        name="walk_distance"
        :value="old.get('walk_distance', location.walk_distance)"
      />
    </div>

    <!-- Links -->
    <div class="mb-6 lg:w-1/2">
      <label class="block mb-2" :class="{ 'text-danger': errors.has('links') }">
        Links
      </label>

      <location-links
        :data-links="
          Object.values(old.get('links', location.links ? location.links : {}))
        "
      ></location-links>
      <input-error data-key="links"></input-error>
    </div>

    <!-- Tags -->
    <div class="w-full md:w-1/2">
      <label
        for="tags"
        class="block mb-2"
        :class="{ 'text-danger': errors.has('tags') }"
      >
        Tags
      </label>
      <location-tags
        class="mb-6"
        data-name="tags"
        :value="old.get('tags', tagIds)"
        :data-allow-create="true"
      ></location-tags>
    </div>

    <!-- Notes -->
    <div class="mb-6">
      <div class="lg:w-1/2">
        <markdown-editor
          data-label="Notes"
          data-name="notes"
          :value="old.get('notes', location.notes)"
        ></markdown-editor>
      </div>
      <input-error data-key="notes"></input-error>
    </div>

    <!-- Icon -->
    <div class="mb-6">
      <div class="flex items-center">
        <label
          for="icon_id"
          class="mr-2"
          :class="{ 'text-danger': errors.has('icon_id') }"
        >
          Icon
        </label>
        <location-icon-select
          data-name="icon_id"
          :value="old.get('icon_id', location.icon_id)"
        ></location-icon-select>
      </div>
      <input-error data-key="icon_id"></input-error>
    </div>

    <!-- Submit -->
    <div>
      <button
        type="submit"
        class="button"
        v-text="isUpdate ? 'Update' : 'Create'"
      ></button>

      <button
        type="submit"
        class="ml-4 button"
        v-if="!isUpdate"
        name="add_new"
        value="1"
      >
        Create and Add New
      </button>
    </div>
  </form>

  <!-- Delete Location (Update/Edit Only) -->
  <form
    method="POST"
    :action="deleteAction"
    class="flex px-4 py-4 bg-white rounded shadow"
    v-if="isUpdate"
  >
    <!-- CSRF Token -->
    <input type="hidden" name="_token" :value="crsfToken" />

    <!-- Method -->
    <input type="hidden" name="_method" value="delete" />

    <confirm-before-submit
      :confirmation-message="`Are you sure you want to delete &quot;${location.name}&quot;? This final and can not be reveresed!`"
    >
      Delete Location
    </confirm-before-submit>
  </form>
</template>

<script>
import { mapState } from "vuex";
import { collect } from "collect.js";
import formChangeWatcher from "../../libs/form-change-watcher";
// import inputName from './_input-name'

export default {
  // components: {inputName},
  props: {
    dataIsUpdate: {
      type: Boolean,
      default: false,
    },
    dataLocation: {
      type: Object,
    },
  },

  data() {
    return {};
  },

  computed: {
    ...mapState([
      "old",
      "errors",
      "timesOfYearToVisit",
      "timesOfDayToVisit",
      "locationTrafficLevels",
      "locationAccessDifficulties",
    ]),
    isUpdate() {
      return this.dataIsUpdate;
    },

    location() {
      return this.dataLocation;
    },

    method() {
      return this.dataIsUpdate ? "PATCH" : undefined;
    },

    action() {
      if (this.isUpdate) {
        return this.$route("locations.update", {
          location: this.location.slug,
        });
      }

      return this.$route("locations.store");
    },

    deleteAction() {
      return this.$route("locations.destroy", { location: this.location.slug });
    },

    crsfToken() {
      return this.$http.defaults.headers.common["X-CSRF-TOKEN"];
    },

    tagIds() {
      return collect(this.location.tags).pluck("id").toArray();
    },

    timesOfYearToVisitOptions() {
      return this.timesOfYearToVisit.map((item) => {
        return {
          label: item,
          value: item,
        };
      });
    },

    timesOfDayToVisitOptions() {
      return this.timesOfDayToVisit.map((item) => {
        return {
          label: item,
          value: item,
        };
      });
    },
  },

  methods: {},

  mounted() {
    formChangeWatcher(this.$refs["form"]);
  },
};
</script>
