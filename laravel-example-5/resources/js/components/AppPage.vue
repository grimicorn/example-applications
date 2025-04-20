<template>
  <div>
    <slot></slot>
  </div>
</template>

<script>
import ModelEvents from "utilities/model-events";
import models from "utilities/models";

export default {
  store: ["user"],

  props: {},

  data() {
    return {
      modelEvents: null
    };
  },

  computed: {},

  methods: {
    handleSiteDeleted() {
      this.modelEvents.deleted("Site", e => {
        this.user.sites = models.removeByKey(this.user.sites, e.modelKey);
      });
    },

    handleSiteSaved() {
      this.modelEvents.saved("Site", e => {
        let sites = this.user.sites;
        this.user.sites = [];
        this.user.sites = models.addByKey(sites, e.modelKey, e.model);
      });
    },

    handleUserSaved() {
      this.modelEvents.saved("User", e => {
        console.log(e.model);
        this.user = e.model;
      });
    }
  },

  created() {
    this.modelEvents = new ModelEvents(this.user);
    this.handleUserSaved();
    this.handleSiteDeleted();
    this.handleSiteSaved();
  }
};
</script>
