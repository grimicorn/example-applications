<style>
  @import "./Pagination.css";
</style>
<template>
  <div>
    <laravel-vue-pagination
      :data="dataPaginator"
      :limit="5"
      @pagination-change-page="handleChangePage"
    >
      <template v-slot:prev-nav>
        <icon data-name="chevron-left"></icon>
        <span class="sr-only">Previous</span>
      </template>
      <template v-slot:next-nav>
        <span class="sr-only">Next</span>
        <icon data-name="chevron-right"></icon>
      </template>
    </laravel-vue-pagination>
  </div>
</template>

<script>
export default {
  props: {
    dataPaginator: {
      type: Object,
      required: true
    }
  },

  data() {
    return {};
  },

  computed: {},

  methods: {
    handleChangePage(page) {
      this.$emit("changed", page);
    }
  }
};
</script>
