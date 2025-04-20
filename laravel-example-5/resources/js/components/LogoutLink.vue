<template>
  <ajax-form
    data-id="logout_form"
    :data-action="route('logout')"
    data-method="POST"
    class="inline"
  >
    <template slot="submit">
      <button
        dusk="logout_form_submit"
        type="submit"
        class="button-link text-sm font-normal p-0"
      >Logout</button>
    </template>
  </ajax-form>
</template>

<script>
export default {
  props: {},

  data() {
    return {};
  },

  computed: {},

  methods: {}
};
</script>
