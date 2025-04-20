<template>
  <div class="bg-white border rounded shadow">
    <!-- Main -->
    <div class="flex px-6 py-4">
      <div class="mr-2">
        <location-icon :data-icon="location.icon"></location-icon>
      </div>
      <div class="flex-1">
        <!-- Main - Header -->
        <div class="flex items-center mb-1">
          <!-- Name -->
          <h2 class="block text-2xl text-primary-900">
            <location-inline-text-edit
              :data-location="location"
              :value="location.name"
              data-label="Name"
              data-name="name"
              @input="(value) => (location.name = value)"
            ></location-inline-text-edit>
          </h2>
          <text-to-clipboard
            :data-text="location.name"
            class="mr-2"
          ></text-to-clipboard>

          <!-- Rating -->
          <location-rating
            class="ml-auto"
            :data-location="location"
          ></location-rating>
        </div>

        <div class="mb-4 md:flex">
          <!-- Main - Start Section -->
          <div class="md:mr-4">
            <!-- Location -->
            <text-to-clipboard
              :data-text="location.address"
              class="text-sm text-gray-600"
              data-label="Copy full address to clipboard"
            >
              <span class="block" v-text="generalLocationLabel"></span>
            </text-to-clipboard>

            <!--
                            @todo Make this work
                            <location-inline-text-edit
                            :data-location="location"
                            :value="location.name"
                            data-label="Name"
                            data-name="name"
                            @input="value => location.name = value"
                        ></location-inline-text-edit> -->

            <!-- Action Bar -->
            <ul class="flex items-center mt-4">
              <!-- Directions -->
              <li class="mr-3">
                <a
                  :href="location.google_maps_link"
                  target="_blank"
                  title="Directions"
                  class="block ml-1"
                >
                  <icon data-name="location" class="w-4 h-4"></icon>
                  <span class="sr-only">Directions</span>
                </a>
              </li>

              <!-- Edit -->
              <li class="mr-3">
                <a :href="$route('locations.edit', { location })" title="Edit">
                  <icon data-name="edit-pencil" class="w-4 h-4"></icon>
                  <span class="sr-only">Edit</span>
                </a>
              </li>

              <!-- View Show -->
              <li class="mr-3" v-if="dataDisplayViewShow">
                <a :href="$route('locations.show', { location })" title="Show">
                  <icon data-name="view-show" class="w-4 h-4"></icon>
                  <span class="sr-only">View</span>
                </a>
              </li>

              <!-- View Links -->
              <li v-if="hasLinks" class="mr-3">
                <button type="button" @click="toggleDrawer" title="View Links">
                  <icon data-name="link" class="w-4 h-4"></icon>
                  <span class="sr-only">Links</span>
                </button>
              </li>

              <!-- View Notes -->
              <li v-if="hasNotes">
                <button type="button" @click="toggleDrawer" title="View Notes">
                  <icon data-name="conversation" class="w-4 h-4"></icon>
                  <span class="sr-only">Notes</span>
                </button>
              </li>
            </ul>
          </div>

          <!-- Main - End Section -->
          <div class="md:ml-auto">
            <!-- Distance From Address -->
            <span
              v-if="location.distance_from_address"
              v-text="
                `${parseFloat(location.distance_from_address, 10).toFixed(
                  2
                )} mi from address`
              "
            ></span>
          </div>
        </div>

        <!-- Tags -->
        <div class="flex">
          <span
            v-for="tag in location.tags"
            :key="tag.id"
            v-text="tag.name"
            class="mr-2 button-sm button"
          ></span>
        </div>
      </div>
    </div>

    <!-- Drawer Toggle -->
    <div class="flex justify-center w-full px-6 py-2 bg-gray-100">
      <button
        type="button"
        class="px-4"
        @click="toggleDrawer"
        v-if="!globalMoreInfoOpen && !dataForceMoreinforOpen"
      >
        <icon
          :data-name="moreInfoOpen ? 'cheveron-up' : 'cheveron-down'"
          class="w-8 h-8"
        ></icon>
      </button>
    </div>

    <!-- Drawer -->
    <div
      :class="{
        block: moreInfoOpen,
        hidden: !moreInfoOpen,
      }"
      class="px-6 py-3 bg-gray-100 rounded-b"
    >
      <div class="md:flex">
        <!-- Drawer - Start Side -->
        <div class="mb-4 md:mr-6">
          <!-- Visited -->
          <div class="mb-3">
            <h3 class="mb-1 font-bold">Visited:</h3>
            <location-inline-select
              :data-location="location"
              :value="location.visited ? '1' : '0'"
              data-label="Visited"
              data-name="visited"
              @input="(value) => (location.visited = value)"
              :dataOptions="[
                { label: 'Yes', value: '1' },
                { label: 'No', value: '0' },
              ]"
            ></location-inline-select>
          </div>

          <!-- Access Difficulty -->
          <div>
            <h3 class="mb-1 font-bold">Access Difficulty:</h3>
            <location-inline-select
              :data-location="location"
              :value="location.access_difficulty"
              data-label="Access Difficulty"
              data-name="access_difficulty"
              @input="(value) => (location.access_difficulty = value)"
              :dataOptions="[
                { label: 'Unknown', value: '' },
                ...locationAccessDifficulties.toArray(),
              ]"
            ></location-inline-select>
          </div>

          <!-- Traffic Level -->
          <div>
            <h3 class="mb-1 font-bold">Traffic Level:</h3>

            <location-inline-select
              :data-location="location"
              :value="location.traffic_level"
              data-label="Traffic Level"
              data-name="traffic_level"
              @input="(value) => (location.traffic_level = value)"
              :dataOptions="[
                { label: 'Unknown', value: '' },
                ...locationTrafficLevels.toArray(),
              ]"
            ></location-inline-select>
          </div>

          <!-- Walk Distance -->
          <div>
            <h3 class="mb-1 font-bold">Walk Distance:</h3>
            <location-inline-number-edit
              :data-location="location"
              :value="location.walk_distance"
              data-label="Walk Distance"
              data-name="walk_distance"
              @input="(value) => (location.walk_distance = value)"
              :data-step="0.01"
              :data-min="0"
              data-display-suffix="miles"
            ></location-inline-number-edit>
          </div>

          <!-- Best Time Of Day To Visit -->
          <div class="mb-3">
            <h3 class="mb-1 font-bold">Time Of Day:</h3>

            <location-inline-select
              :data-location="location"
              :value="
                location.best_time_of_day_to_visit
                  ? location.best_time_of_day_to_visit
                  : ''
              "
              data-label="Best Time Of Day To Visit"
              data-name="best_time_of_day_to_visit"
              @input="(value) => (location.best_time_of_day_to_visit = value)"
              :dataOptions="[
                { label: 'Any', value: '' },
                ...timesOfDayToVisit.map((time) => {
                  return { label: time, value: time };
                }),
              ]"
            ></location-inline-select>
          </div>

          <!-- Best Time Of Year To Visit -->
          <div class="mb-3">
            <h3 class="mb-1 text-sm font-bold">Time Of Year:</h3>
            <location-inline-select
              :data-location="location"
              :value="
                location.best_time_of_year_to_visit
                  ? location.best_time_of_year_to_visit
                  : ''
              "
              data-label="Best Time Of Year To Visit"
              data-name="best_time_of_year_to_visit"
              @input="(value) => (location.best_time_of_year_to_visit = value)"
              :dataOptions="[
                { label: 'Any', value: '' },
                ...timesOfYearToVisit.map((time) => {
                  return { label: time, value: time };
                }),
              ]"
            ></location-inline-select>
          </div>

          <!-- Sunrise -->
          <div class="px-4 pt-4 pb-2 mb-4 bg-gray-200 rounded">
            <h3 class="mb-2 font-bold">Sunrise</h3>
            <ul class="whitespace-nowrap">
              <li v-if="location.sunrise_blue_hour" class="mb-2">
                <strong class="text-gray-700">Blue Hour:</strong>
                ~@{{ dateFormat(location.sunrise_blue_hour, "h:mm a") }}
              </li>
              <li v-if="location.sunrise" class="mb-2">
                <strong class="text-gray-700">Sunrise:</strong>
                ~@{{ dateFormat(location.sunrise, "h:mm a") }}
              </li>
              <li v-if="location.sunrise_golden_hour" class="mb-2">
                <strong class="text-gray-700">Golden Hour:</strong>
                ~@{{ dateFormat(location.sunrise_golden_hour, "h:mm a") }}
              </li>
            </ul>
          </div>

          <!-- Sunset -->
          <div class="px-4 pt-4 pb-2 bg-gray-200 rounded">
            <h3 class="mb-2 font-bold">Sunset</h3>
            <ul class="whitespace-nowrap">
              <li v-if="location.sunset_golden_hour" class="mb-2">
                <strong class="text-gray-700">Golden Hour:</strong>
                ~@{{ dateFormat(location.sunset_golden_hour, "h:mm a") }}
              </li>
              <li v-if="location.sunset" class="mb-2">
                <strong class="text-gray-700">Sunset:</strong>
                ~@{{ dateFormat(location.sunset, "h:mm a") }}
              </li>
              <li v-if="location.sunset_blue_hour" class="mb-2">
                <strong class="text-gray-700">Blue Hour:</strong>
                ~@{{ dateFormat(location.sunset_blue_hour, "h:mm a") }}
              </li>
            </ul>
          </div>
        </div>

        <!-- Drawer - End Side -->
        <div class="flex-1">
          <!-- Location Notes -->
          <location-inline-markdown-edit
            class="mb-4"
            :data-location="location"
            :value="location.notes ? location.notes : ''"
            data-label="Notes"
            data-name="notes"
            @input="(value) => (location.notes = value)"
          ></location-inline-markdown-edit>

          <!-- Location Links -->
          <ul v-if="location.links">
            <li
              v-for="link in location.links"
              :key="link.id"
              class="flex items-center mb-2"
            >
              <icon
                data-name="link"
                class="w-4 h-4 mr-2 text-primary-900"
              ></icon>
              <a :href="link.url" v-text="link.name" target="_blank"></a>
            </li>
          </ul>

          <!-- Location Images -->
          <location-images
            :data-search-query="locationImagesSearchQuery"
            v-if="moreInfoOpen && dataDisplayLocationImages"
          ></location-images>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import collect from "collect.js";
import { format as dateFormat } from "date-fns";
import { mapState } from "vuex";

export default {
  props: {
    dataLocation: {
      type: Object,
      required: true,
    },

    dataForceMoreinforOpen: {
      type: Boolean,
      default: false,
    },

    dataDisplayLocationImages: {
      type: Boolean,
      default: false,
    },

    dataDisplayViewShow: {
      type: Boolean,
      default: true,
    },
  },

  data() {
    return {
      localMoreInfoOpen: false,
    };
  },

  computed: {
    locationImagesSearchQuery() {
      return `${this.location.name} ${this.generalLocationLabel}`;
    },

    generalLocationLabel() {
      return [
        this.location.locality,
        this.location.administrative_area_level_1_abbreviation,
      ]
        .filter((value) => !!value)
        .join(", ");
    },

    moreInfoOpen() {
      return (
        this.globalMoreInfoOpen ||
        this.localMoreInfoOpen ||
        this.dataForceMoreinforOpen
      );
    },

    ...mapState([
      "timesOfYearToVisit",
      "timesOfDayToVisit",
      "globalMoreInfoOpen",
      "locationTrafficLevels",
      "locationAccessDifficulties",
    ]),

    location() {
      Object.entries(this.dataLocation).forEach(([key, value]) => {
        this.dataLocation[key] = Array.isArray(value) ? collect(value) : value;
      });

      return this.dataLocation;
    },

    hasLinks() {
      return this.location.links && !this.location.links.isEmpty();
    },

    hasNotes() {
      return this.location.notes;
    },
  },

  methods: {
    dateFormat(date, format) {
      return dateFormat(new Date(date), format);
    },
    toggleDrawer() {
      this.localMoreInfoOpen = !this.localMoreInfoOpen;
    },
  },

  watch: {
    globalMoreInfoOpen(value) {
      if (!value) {
        this.localMoreInfoOpen = false;
      }
    },
  },
};
</script>
