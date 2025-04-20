<template>
  <nav
    v-if="paginator.last_page > 1"
    role="navigation"
    aria-label="Pagination Navigation"
    class="flex items-center justify-between"
  >
    <div class="flex items-center justify-between flex-1">
      <div>
        <pagination-details
          :dataPaginator="dataPaginator"
          class="mb-6"
        ></pagination-details>
      </div>

      <div>
        <span class="relative z-0 inline-flex shadow-sm">
          <!-- Previous Page Link -->
          <span
            v-if="paginator.current_page === 1"
            aria-disabled="true"
            aria-label="&laquo; Previous"
          >
            <span
              class="relative inline-flex items-center px-2 py-2 text-sm font-medium leading-5 text-white border border-gray-300 cursor-default bg-primary-900 rounded-l-md"
              aria-hidden="true"
            >
              <icon data-name="chevron-left" class="w-5 h-5" />
            </span>
          </span>
          <a
            v-if="paginator.current_page !== 1"
            :href="paginator.prev_page_url"
            rel="prev"
            class="relative inline-flex items-center px-2 py-2 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-l-md hover:text-gray-400 focus:z-10 focus:outline-none focus:border-primary-300 focus:ring-primary active:bg-gray-100 active:text-gray-500"
            aria-label="&laquo; Previous"
          >
            <icon data-name="chevron-left" class="w-5 h-5" />
          </a>

          <!-- Pagination Elements -->
          <div v-for="(element, index) in elements" :key="index">
            <!-- "Three Dots" Separator -->
            <span aria-disabled="true" v-if="typeof element === 'string'">
              <span
                class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5 text-gray-700 bg-white border border-gray-300 cursor-default"
                v-text="element"
              ></span>
            </span>

            <!-- Array Of Links -->
            <div v-if="Array.isArray(element)" class="flex">
              <div v-for="{ url, page } in element" :key="url">
                <span
                  aria-current="page"
                  v-if="paginator.current_page === page"
                >
                  <span
                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5 text-white border border-gray-300 cursor-default bg-primary-900"
                    v-text="page"
                  ></span>
                </span>
                <a
                  v-if="paginator.current_page !== page"
                  :href="url"
                  class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 hover:text-white hover:bg-primary-900 focus:z-10 focus:outline-none focus:border-primary-300 focus:ring-primary active:bg-gray-100 active:text-gray-700"
                  :aria-label="`Go to page ${page}`"
                  v-text="page"
                >
                </a>
              </div>
            </div>
          </div>

          <!-- Next Page Link -->
          <a
            v-if="paginator.last_page > paginator.current_page"
            :href="paginator.next_page_url"
            rel="next"
            class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-r-md hover:text-gray-400 focus:z-10 focus:outline-none focus:border-primary-300 focus:ring-primary active:bg-gray-100 active:text-gray-500"
            aria-label="Next &raquo;"
          >
            <icon data-name="chevron-right" class="w-5 h-5" />
          </a>
          <span
            v-if="paginator.current_page >= paginator.last_page"
            aria-disabled="true"
            aria-label="Next &raquo;"
          >
            <span
              class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium leading-5 text-white border border-gray-300 cursor-default bg-primary-900 rounded-r-md"
              aria-hidden="true"
            >
              <icon data-name="chevron-right" class="w-5 h-5" />
            </span>
          </span>
        </span>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  props: {
    dataPaginator: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {};
  },

  computed: {
    paginator() {
      return this.dataPaginator;
    },

    elements() {
      // @todo add ... for larger sets of pagination
      return [
        Array(this.paginator.last_page)
          .fill(null)
          .map((_value, index) => {
            const page = index + 1;
            return { url: this.getUrlForPage(page), page: page };
          }),
      ];
    },
  },

  methods: {
    getUrlForPage(page) {
      return this.paginator.first_page_url
        .replace("&page=1", `&page=${page}`)
        .replace("?page=1", `?page=${page}`);
    },
  },
};
</script>
