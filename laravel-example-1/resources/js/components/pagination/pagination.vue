<template>
  <div>
    <pagination-complex :dataPaginator="dataPaginator"></pagination-complex>
    <pagination-simple
      class="sm:hidden"
      :dataPaginator="dataPaginator"
    ></pagination-simple>
  </div>
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

  computed: {},

  methods: {},
};
</script>
