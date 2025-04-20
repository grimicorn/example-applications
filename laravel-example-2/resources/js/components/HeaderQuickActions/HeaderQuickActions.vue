<style lang="scss" scoped>
  @import "./HeaderQuickActions.css";
</style>
<template>
  <div class="relative">
    <button
      class="block flex justify-start items-center"
      @click="toggle"
      @focus="handleFocus"
      @blur="close"
    >
      <span class="sr-only">Quick Actions</span>
      <icon
        data-name="user-circle"
        class="mr-2"
      ></icon>
      <icon data-name="chevron-down"></icon>
    </button>
    <ul
      class="dropdown-content p-3 pt-5 absolute shadow-lg bg-white right-0 rounded-sm"
      ref="dropdown"
      v-if="isOpen"
    >
      <li>
        <form
          id="logout-form"
          :action="logoutRoute"
          method="POST"
        >
          <input
            type="hidden"
            name="_token"
            :value="csrfToken"
          >

          <button type="submit">Logout</button>
        </form>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isOpen: this.dataOpen
    };
  },

  computed: {
    logoutRoute() {
      return route("logout");
    },

    csrfToken() {
      return this.$http.defaults.headers.common["X-CSRF-TOKEN"];
    }
  },

  methods: {
    toggle() {
      if (this.isOpen) {
        return this.close();
      }

      this.open();
    },

    open() {
      this.isOpen = true;
      this.$emit("opened");

      this.$nextTick(() => this.$refs["dropdown"].focus());
    },

    close() {
      this.isOpen = false;
      this.$emit("closed");
    },

    handleFocus() {
      setTimeout(this.open, 300);
    }
  },

  watch: {
    dataOpen(value) {
      this.dataOpen = value;
    }
  }
};
</script>
