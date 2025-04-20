<template>
  <form class="mb-8" :action="action" @submit.prevent="handleSubmit" ref="form">
    <div>
      <div class="flex flex-col items-center w-full mb-4">
        <div class="flex-wrap items-center w-full mb-4 sm:flex">
          <!-- Search By -->
          <div class="sm:mr-6">
            <label for="search_by">Search By:</label>
            <select
              id="search_by"
              name="search_by"
              @input="(value) => handleSearchByUpdate(value)"
              :value="locationsIndexForm.search_by"
            >
              <option value="address">Address</option>
              <option value="keyword">Keyword</option>
            </select>
          </div>

          <!-- Address -->
          <div
            v-if="locationsIndexForm.search_by === 'address'"
            class="relative flex flex-col flex-1 w-full mb-4  sm:mr-6 sm:w-auto sm:mb-0"
          >
            <div>
              <label for="address" class="sr-only">Address</label>
              <div class="flex items-center">
                <location-autocomplete
                  name="address"
                  id="address"
                  @input="(value) => handleInputUpdateEvent(value, 'address')"
                  :value="locationsIndexForm.address"
                ></location-autocomplete>

                <current-location-button></current-location-button>
              </div>
            </div>
          </div>

          <!-- Search -->
          <div
            v-if="locationsIndexForm.search_by === 'keyword'"
            class="relative flex flex-col flex-1 w-full mb-4  sm:mr-6 sm:w-auto sm:mb-0"
          >
            <label for="search" class="sr-only">Search</label>
            <clearable-input
              type="search"
              @input="(value) => handleInputUpdateEvent(value, 'search')"
              name="search"
              placeholder="Location Name"
              :value="locationsIndexForm.search"
            />
          </div>

          <!-- Max Distance -->
          <div
            v-if="locationsIndexForm.search_by === 'address'"
            class="w-full mb-4 sm:mr-6 sm:w-auto sm:mb-0"
          >
            <label for="max_distance" class="sr-only">Max Distance</label>
            <div class="flex items-center">
              <input
                type="number"
                name="filter[max_distance]"
                id="max_distance"
                step="50"
                min="0"
                placeholder="∞"
                class="w-16 mr-2"
                @input="
                  (event) =>
                    handleInputUpdateEvent(event, 'filter[max_distance]')
                "
                :value="locationsIndexForm.filter.max_distance"
              />
              miles
            </div>
          </div>

          <!-- Rating -->
          <div class="flex items-center w-full sm:w-auto sm:mb-0">
            <div class="mr-1">
              <select
                name="filter[min_rating]"
                id="min_rating"
                @input="
                  (event) => handleInputUpdateEvent(event, 'filter[min_rating]')
                "
                v-model="locationsIndexForm.filter.min_rating"
              >
                <option
                  v-for="option in ratingOptions"
                  :key="option.value"
                  :value="option.value"
                  :selected="option.selected"
                  v-text="option.label"
                ></option>
              </select>
            </div>
            <label for="min_rating">
              <span class="sr-only">Minimum Rating</span>/5
            </label>
          </div>
        </div>

        <!-- Address Distance -->
        <search-address-distance
          v-if="locationsIndexForm.search_by === 'address'"
        ></search-address-distance>

        <!-- Search Location -->
        <search-location
          v-if="locationsIndexForm.search_by === 'address'"
          :data-address="locationsIndexForm.address"
          class="mb-4"
          @input="(value) => handleInputUpdateEvent(value, 'address')"
        ></search-location>

        <div class="items-center w-full mb-4 sm:flex">
          <!-- Per Page -->
          <div class="flex items-center w-full mb-4 sm:mr-6 sm:w-auto sm:mb-0">
            <div class="mr-2">
              <select
                name="per_page"
                id="per_page"
                @input="(event) => handleInputUpdateEvent(event, 'per_page')"
                :value="locationsIndexForm.per_page"
              >
                <option
                  v-for="option in perPageOptions"
                  :key="option.value"
                  :value="option.value"
                  :selected="option.selected"
                  v-text="option.label"
                ></option>
              </select>
            </div>
            <label for="per_page">per page</label>
          </div>

          <!-- Sort Boolean -->
          <input-boolean
            :value="locationsIndexForm.sort === 'distance'"
            @input="(event) => handleInputUpdateEvent(event, 'sort')"
            data-name="sort"
            :data-input-true="{
              label: 'Asc',
              value: 'distance',
            }"
            :data-input-false="{
              label: 'Desc',
              value: '-distance',
            }"
            class="mb-4 sm:mr-6 sm:mb-0"
          ></input-boolean>

          <!-- Visited -->
          <div class="flex items-center w-full mr-1 sm:w-auto sm:mb-0">
            <label for="visited" class="sr-only"> Visited </label>
            <select
              name="filter[visited]"
              id="visited"
              @input="
                (event) => handleInputUpdateEvent(event, 'filter[visited]')
              "
              :value="locationsIndexForm.filter.visited"
            >
              <option value="">All</option>
              <option value="1">Visited</option>
              <option value="0">Not Visited</option>
            </select>
          </div>

          <!-- List/Map Boolean -->
          <input-boolean
            :value="locationsIndexForm.map.toString() === '0'"
            @input="(event) => handleInputUpdateEvent(event, 'map')"
            data-name="map"
            :data-input-true="{
              label: 'List',
              value: '0',
            }"
            :data-input-false="{
              label: 'Map',
              value: '1',
            }"
            class="mb-4 ml-auto sm:mb-0"
          ></input-boolean>
        </div>

        <!-- Tags -->
        <div class="w-full">
          <div class="w-full md:w-1/2">
            <label for="filter[tags]" class="block mb-2">Tags</label>
            <location-tags
              class="mb-4"
              :value="
                locationsIndexForm.filter.tags
                  ? locationsIndexForm.filter.tags
                  : []
              "
              data-name="filter[tags]"
              @input="(value) => handleInputUpdate(value, 'filter[tags]')"
            ></location-tags>
          </div>
        </div>

        <div class="flex w-full">
          <!-- Reset -->
          <button
            type="reset"
            class="w-full button-outline sm:w-auto"
            @click="reset"
          >
            Reset
          </button>

          <!-- Submit -->
          <button type="submit" class="w-full button sm:w-auto sm:ml-auto">
            Update
          </button>
        </div>
      </div>
    </div>
  </form>
</template>
<script>
import { mapState } from 'vuex';
import Router from '@libs/router';
import _filter from 'lodash.filter';
import SearchLocation from './_search-location/_search-location';

export default {
  components: {
    SearchLocation,
  },

  props: {},

  data() {
    return {
      autoResetable: true,
    };
  },

  computed: {
    ...mapState(['locationsIndexForm']),

    action() {
      return this.$route('locations.index');
    },

    perPageOptions() {
      return [
        { label: '15', value: 15 },
        { label: '25', value: 25 },
        { label: '50', value: 50 },
        { label: '100', value: 100 },
      ].map((rating) => {
        rating.selected =
          rating.value === parseInt(this.locationsIndexForm.per_page, 10);
        return rating;
      });
    },

    ratingOptions() {
      return [
        {
          label: 'All',
          value: '',
        },
        {
          label: 'Unrated',
          value: -1,
        },
        {
          label: '1+',
          value: 1,
        },
        {
          label: '2+',
          value: 2,
        },
        {
          label: '3+',
          value: 3,
        },
        {
          label: '4+',
          value: 4,
        },
        {
          label: '5',
          value: 5,
        },
      ].map((rating) => {
        rating.selected =
          rating.value ===
          parseInt(this.locationsIndexForm.filter.min_rating, 10);

        return rating;
      });
    },
  },

  methods: {
    handleInputUpdateEvent(event, name) {
      this.handleInputUpdate(
        event && event.target ? event.target.value : event,
        name
      );

      if (name === 'map') {
        this.$nextTick(this.submit);
      }
    },

    handleInputUpdate(value, name) {
      this.$store.commit('updateLocationsIndexFormField', {
        name,
        value,
      });
    },

    reset() {
      this.$store.commit('resetLocationsIndexForm');
      this.$nextTick(this.submit);
    },

    resetWithoutSubmit() {
      this.$store.commit('updatePaginatedLocations', {});
      this.$store.commit('updateLocationsLoading', false);
      this.$store.commit('resetLocationsIndexForm');
      Router.push(this.getSearchUrl());
    },

    getQueryParameterString() {
      const topLevelParams = Object.entries(this.locationsIndexForm)
        .filter(([_key, value]) => !!value)
        .filter(([key, _value]) => key !== 'filter')
        .map(([key, value]) => `${key}=${encodeURIComponent(value)}`);

      const filterParams = Object.entries(this.locationsIndexForm.filter ?? {})
        .filter(([_key, value]) => !!value)
        .map(([key, value]) => {
          if (!Array.isArray(value)) {
            return `filter[${key}]=${encodeURIComponent(value)}`;
          }

          return value
            .filter((item) => !!item)
            .map((item) => `filter[${key}][]=${encodeURIComponent(item)}`)
            .join('&');
        });

      return [...topLevelParams, ...filterParams]
        .filter((param) => !!param)
        .join('&');
    },

    getSearchUrl() {
      return [
        this.$route('locations.index').toString(),
        this.getQueryParameterString(),
      ]
        .filter((param) => !!param)
        .join('?');
    },

    handleSubmit() {
      this.handleInputUpdate(1, 'page');
      this.$nextTick(this.submit);
    },

    submit() {
      this.$store.commit('updateLocationsLoading', true);

      this.$http
        .get(this.getSearchUrl())
        .then((response) => {
          // Update Search Address Distance
          const searchAddressDistance =
            response.data.data.searchAddressDistance;
          this.$store.commit(
            'setUserSearchAddressDistance',
            searchAddressDistance
          );

          // Update paginated locations
          const paginatedLocations = response.data.data.locations;
          if (paginatedLocations) {
            Router.push(this.getSearchUrl());
            this.$store.commit('updatePaginatedLocations', paginatedLocations);
            this.autoResetable = true;
          }

          this.$store.commit('updateLocationsLoading', false);
        })
        .catch(() => {
          // We do not want to get in an infinite loop in case something is actually wrong on the server.
          if (!this.autoResetable) {
            this.resetWithoutSubmit();
            return;
          }

          this.autoResetable = false;
          this.reset();
        });
    },

    handleSearchByUpdate(value) {
      this.handleInputUpdateEvent('', 'search');
      this.handleInputUpdateEvent('', 'address');
      this.handleInputUpdateEvent(value, 'search_by');
    },
  },

  mounted() {
    this.submit();
  },
};
</script>
