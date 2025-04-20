<template>
    <ajax-form
        class="inline-flex"
        v-if="showForm"
        :data-id="formId"
        :data-action="action"
        :data-auto-submit="true"
        data-method="PATCH"
        v-on-clickaway="toggleShowForm"
    >
        <template slot="inputs">
            <input-text
                data-name="difference_threshold"
                :data-id="inputId"
                data-type="number"
                :data-value="threshold"
                :data-min="0"
                :data-max="1"
                :data-step="0.05"
                class="mb-0 w-16 mr-3"
                :data-autofocus="true"
                @keyup.esc="toggleShowForm"
            ></input-text>

            <button type="button" class="button-link p-0" @click="toggleShowForm">
                <span class="sr-only">Close</span>
                <icon data-name="close-outline" data-icon-class="h-4 w-4 fill-current cursor-pointer"></icon>
            </button>
        </template>
    </ajax-form>

    <span
        v-else
        v-text="thresholdLabel"
        title="Click to edit"
        @click="toggleShowForm"
        class="w-16 cursor-pointer"
    ></span>
</template>

<script>
import { mixin as clickaway } from "vue-clickaway";

export default {
    mixins: [clickaway],

    props: {
        dataPage: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            index: "0",
            showForm: false,
            threshold: this.dataPage.difference_threshold
        };
    },

    computed: {
        thresholdLabel() {
            let threshold = Math.floor(this.threshold * 100);

            return `${threshold}%`;
        },

        formId() {
            return `page_threshold_${this.dataPage.id}_form`;
        },

        inputId() {
            return `page_threshold_${this.dataPage.id}_input`;
        },

        action() {
            return this.route("site.pages.threshold.update", {
                id: this.dataPage
            });
        }
    },

    methods: {
        toggleShowForm() {
            this.showForm = !this.showForm;
        }
    },

    watch: {
        dataPage() {
            this.threshold = this.dataPage.difference_threshold;
        }
    }
};
</script>
