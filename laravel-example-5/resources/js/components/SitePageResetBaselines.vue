<template>
    <ajax-form
        data-id="site_baselines_update"
        data-method="PUT"
        :data-action="action"
    >
        <template
            slot="submit"
            slot-scope="{disabled, submitDusk}"
        >
            <button
                class="flex items-center h-full button"
                :dusk="submitDusk"
                type="submit"
                :disabled="disabled"
            >
                <icon
                    class="mr-1"
                    data-icon-class="fill-current w-4 h-4"
                    data-name="add-outline"
                ></icon>
                Reset Baselines
            </button>
        </template>
    </ajax-form>
</template>

<script>
export default {
    props: {
        dataSite: {
            type: Object,
            required: true
        }
    },

    data() {
        return {};
    },

    computed: {
        action() {
            return this.route("site.baselines.update", { site: this.site });
        },

        site() {
            return this.dataSite;
        }
    },

    methods: {}
};
</script>
