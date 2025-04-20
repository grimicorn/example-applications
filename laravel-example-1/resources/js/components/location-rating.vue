<template>
  <ul class="flex items-center">
    <li class="mr-1" v-for="rating in ratings" :key="rating">
      <form
        :action="$route('location-rating', { location })"
        method="POST"
        @submit.prevent="() => handleSubmit(rating)"
      >
        <input type="hidden" name="_method" value="PATCH" />

        <input type="hidden" name="_token" :value="csrfToken" />

        <input type="hidden" :value="rating" name="rating" />

        <button
          type="submit"
          :title="`${rating}/5`"
          class="text-primary-900"
          :disabled="submitting"
        >
          <span class="sr-only" v-text="`${rating}/5`"></span>
          <icon
            class="w-4 h-6"
            :data-name="currentRating >= rating ? 'star' : 'star-outline'"
          />
        </button>
      </form>
    </li>
    <li
      class="text-xs"
      v-text="`(${currentRating ? currentRating : '?'}/5)`"
    ></li>
  </ul>
</template>

<script>
export default {
  props: {
    dataLocation: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      ratings: [1, 2, 3, 4, 5],
      currentRating: this.dataLocation.rating,
      submitting: false,
    };
  },

  computed: {
    location() {
      return this.dataLocation;
    },

    csrfToken() {
      return this.$http.defaults.headers.common["X-CSRF-TOKEN"];
    },
  },

  methods: {
    handleSubmit(rating) {
      if (this.submitting) {
        return;
      }

      this.submitting = true;

      this.$http
        .patch(
          this.$route("location-rating", { location: this.location.slug }),
          { rating: rating }
        )
        .then((response) => {
          this.currentRating = rating;
          this.submitting = false;
        })
        .catch(() => (this.submitting = false));
    },
  },

  watch: {
    location({ rating }) {
      this.currentRating = rating;
    },
  },
};
</script>
