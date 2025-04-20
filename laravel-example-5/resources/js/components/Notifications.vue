<template>
  <dropdown
    class="mr-4 notifications-wrap"
    data-placement="bottom"
  >
    <template
      slot="toggle"
      slot-scope="{ toggle, isOpen }"
    >
      <div class="h-8 py-6 flex items-center">
        <div class="relative">
          <icon
            @click="toggle"
            data-name="notifications"
            data-icon-class="cursor-pointer h-5 w-5 fill-current block"
            :class="{
              'text-grey-light': !isOpen,
              'text-grey-darker': isOpen,
            }"
          ></icon>

          <span
            v-if="unreadTotal"
            class="w-2 h-2 rounded-full block absolute pin-t pin-r pointer-events-none"
            :class="{
              'bg-accent-light': !isOpen,
              'bg-accent': isOpen,
            }"
          ></span>
        </div>
      </div>
    </template>

    <template slot="dropdown">
      <template v-if="notifications.length">
        <ul
          class="notifications"
          :class="{'mb-0': !hasMore}"
        >
          <li>
            <notification
              v-for="notification in notifications"
              :key="notification.id"
              :data-notification="notification"
              @deleted="handleDeleted"
            ></notification>
          </li>
        </ul>

        <div
          v-if="hasMore"
          class="flex justify-center mt-2 pt-2 border-t"
        >
          <button
            type="button"
            class="button-link button-small"
            @click="getNext"
            :disabled="disabled"
          >
            <loader v-if="loading"></loader>
            <span
              v-else
              v-text="moreLabel"
            ></span>
          </button>
        </div>
      </template>

      <div
        class="text-center whitespace-no-wrap"
        v-else
      >
        No notifications.
      </div>
    </template>
  </dropdown>
</template>

<script>
import Models from "utilities/models";

export default {
  props: {
    dataPerPage: {
      type: Number,
      default: 5
    }
  },

  data() {
    return {
      notifications: [],
      perPage: this.dataPerPage,
      moreLabel: "Load More",
      hasMore: null,
      page: 1,
      total: null,
      loading: true,
      unreadTotal: null
    };
  },

  computed: {
    url() {
      return route("notifications.index", {
        per_page: this.perPage,
        page: this.page
      });
    },

    disabled() {
      return this.loading || !this.hasMore;
    }
  },

  methods: {
    getNext() {
      if (this.hasMore === false) {
        return;
      }

      this.$http
        .get(this.url)
        .then(response => {
          this.page = response.data.notifications.current_page + 1;
          this.notifications = [
            ...response.data.notifications.data,
            ...this.notifications
          ];
          this.hasMore = !!response.data.notifications.next_page_url;
          this.total = response.data.notifications.total;
          this.loading = false;
          this.unreadTotal = response.data.unread_total;
        })
        .catch(error => {
          this.loading = false;
        });
    },

    handleDeleted(notification) {
      this.notifications = Models.removeByKey(
        this.notifications,
        notification.id
      );
    }
  },

  created() {
    this.getNext();
  }
};
</script>
