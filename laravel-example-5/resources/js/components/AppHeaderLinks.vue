<style lang="scss" scoped></style>

<template>
  <dropdown dusk="app_header_links">
    <template
      slot="toggle"
      slot-scope="{ toggle }"
    >
      <img
        :src="user.avatar"
        class="w-8 h-8 rounded-full cursor-pointer block my-2"
        @click="toggle"
      >
    </template>

    <template slot="dropdown">
      <ul class="list-reset text-left">
        <li>
          <a
            :href="route('profile.edit', {user: user})"
            class="button-link text-sm text-sm p-0"
          >
            Profile
          </a>
        </li>

        <li>
          <logout-link></logout-link>
        </li>
      </ul>
    </template>
  </dropdown>
</template>

<script>
export default {
  store: ["user"],

  props: {},

  data() {
    return {};
  },

  computed: {},

  methods: {}
};
</script>
