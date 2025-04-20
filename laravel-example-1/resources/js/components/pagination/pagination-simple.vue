<template>
  <nav
    v-if="paginator.last_page > 1"
    role="navigation"
    aria-label="Pagination Navigation"
    class="flex justify-between"
  >
    <!-- Previous Page Link -->
    <span
      v-if="paginator.current_page === 1"
      class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-white border border-gray-300 rounded-md cursor-default bg-primary-900"
    >
      &laquo; Previous
    </span>
    <a
      v-if="paginator.current_page !== 1"
      :href="paginator.prev_page_url"
      rel="prev"
      class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md hover:text-white hover:bg-primary-900 focus:outline-none focus:ring-primary focus:border-primary-300 active:bg-gray-100 active:text-gray-700"
    >
      &laquo; Previous
    </a>

    <!-- Next Page Link -->
    <a
      v-if="paginator.last_page > paginator.current_page"
      :href="paginator.next_page_url"
      rel="next"
      class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md hover:text-white hover:bg-primary-900 focus:outline-none focus:ring-primary focus:border-primary-300 active:bg-gray-100 active:text-gray-700"
    >
      Next &raquo;
    </a>
    <span
      v-if="paginator.current_page >= paginator.last_page"
      class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-white border border-gray-300 rounded-md cursor-default bg-primary-900"
    >
      Next &raquo;
    </span>
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
  },

  methods: {},
};
</script>
