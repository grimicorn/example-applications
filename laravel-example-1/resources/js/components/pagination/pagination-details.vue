<template>
  <div class="text-sm leading-5 text-gray-700" v-if="dataPaginator.total > 0">
    Showing
    <span class="font-medium" v-text="dataPaginator.from"></span>
    to
    <span class="font-medium" v-text="dataPaginator.to"></span>
    of
    <span class="font-medium" v-text="dataPaginator.total"></span>
    results
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
