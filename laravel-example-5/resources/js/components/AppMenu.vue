<template>
  <ul class="list-reset py-4 text-white">
    <li>
      <div class="flex mb-1">
        <a
          :href="route('sites.index')"
          class="text-white no-underline mr-2"
        >Sites</a>

        <a
          :href="route('sites.create')"
          title="Add New Site"
        >
          <icon
            data-name="add-outline"
            data-icon-class="h-3 w-3 fill-current"
          ></icon>
          <span class="sr-only">Add New</span>
        </a>
      </div>

      <ul
        class="list-reset"
        v-if="sites"
      >
        <li
          v-for="site in sites"
          :key="site.id"
          class="mb-1 pb-1 border-b ml-2"
        >
          <a
            :href="route('sites.show', {site: site})"
            class="text-white no-underline text-xs"
            v-text="site.name"
          ></a>
        </li>
      </ul>
    </li>

    <li v-if="this.user.is_admin">
      <a
        :href="route('styleguide.index')"
        class="text-white no-underline mb-1 block"
      >Styleguide</a>
      <a
        class="text-white no-underline mb-1 block"
        href="/nova"
        target="_blank"
      >
        <strong>Laravel</strong> Nova
      </a>

      <a
        class="text-white no-underline mb-1 block"
        href="/horizon"
        target="_blank"
      >
        <strong>Laravel</strong> Horizon
      </a>

      <a
        class="text-white no-underline mb-1 block"
        href="/telescope"
        target="_blank"
      >
        <strong>Laravel</strong> Telescope
      </a>
    </li>
  </ul>
</template>

<script>
export default {
  store: ["user"],

  props: {},

  data() {
    return {};
  },

  computed: {
    sites() {
      return this.user.sites;
    }
  },

  methods: {}
};
</script>
