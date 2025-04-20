<template>
  <div class="">
    Listening...
    <ul>
      <li
        v-for="(message,key) in messages"
        :key="key"
        v-text="message"
      ></li>
    </ul>
  </div>

</template>

<script>
export default {
  store: ["user"],

  props: {},

  data() {
    return {
      messages: []
    };
  },

  computed: {},

  methods: {},

  created() {
    window.Echo.private(`user.${this.user.id}`).listen(
      "BroadcastingModelEvent",
      e => {
        this.messages.push(`${e.modelType} ${e.eventType}`);
      }
    );
  }
};
</script>
