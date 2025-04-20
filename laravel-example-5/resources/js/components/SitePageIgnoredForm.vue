<template>
    <input-toggle
        class="mb-0 inline-block"
        :data-name="inputName"
        :data-value="ignored"
        :data-disabled="updating"
        v-model="ignored"
        v-if="showToggle"
        v-on-clickaway="closeToggle"
        :data-autofocus="true"
        @keyup.esc="closeToggle"
    />
    <span
        @click="showToggle = true"
        class="status-pill cursor-pointer"
        :class="{
            warning: ignored,
            success: !ignored
        }"
        v-else
        v-text="ignored ? 'Yes' : 'No'"
    ></span>
</template>

<script>
import { mixin as clickaway } from "vue-clickaway";

export default {
    mixins: [clickaway],

    store: ["addAlert", "messages"],

    props: {
        dataPage: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            ignored: this.dataPage.ignored,
            page: this.dataPage,
            updating: false,
            skipUpdate: false,
            showToggle: false
        };
    },

    computed: {
        inputName() {
            return `page_ignored_${this.page.id}_input`;
        },

        updateAction() {
            return this.route("site.pages.ignore.update", {
                page: this.page.id
            });
        },

        destroyAction() {
            return this.route("site.pages.ignore.destroy", {
                page: this.page.id
            });
        }
    },

    methods: {
        handleResponse(response) {
            this.addAlert(response.data.status, "success", 5000);
            this.updating = false;
        },

        handleError(error) {
            this.addAlert(this.messages.genericError, "danger", 5000);

            this.skipUpdate = true;
            this.ignored = !this.ignored;
            this.updating = false;
        },

        ignore() {
            this.$http
                .patch(this.updateAction)
                .then(this.handleResponse)
                .catch(this.handleError);
        },

        unignore() {
            this.$http
                .delete(this.destroyAction)
                .then(this.handleResponse)
                .catch(this.handleError);
        },

        toggle() {
            if (this.skipUpdate || this.updating) {
                this.skipUpdate = false;
                return;
            }

            this.updating = true;

            if (this.ignored) {
                this.ignore();
            } else {
                this.unignore();
            }
        },

        closeToggle() {
            this.showToggle = false;
        }
    },

    watch: {
        ignored() {
            this.toggle();
        },

        dataPage() {
            this.skipUpdate = true;
            this.ignored = this.dataPage.ignored;
        }
    }
};
</script>
