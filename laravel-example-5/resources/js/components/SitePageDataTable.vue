<template>
    <data-table
        :data-headers="headers"
        :data-url="route('sites.show', site)"
        :data-id="id"
        data-item-model="SitePage"
    >
        <template slot="toolbar">
            <!-- Generate Snapshots -->
            <site-page-generate-snapshots
                :data-site="site"
                class="mr-4"
            ></site-page-generate-snapshots>

            <!-- Reset Baselines -->
            <site-page-reset-baselines
                :data-site="site"
                class="mr-4"
            ></site-page-reset-baselines>

            <a
                :href="route('sites.edit', site)"
                class="button flex items-center"
            >
                <icon
                    class="mr-1"
                    data-icon-class="fill-current w-3 h-3"
                    data-name="edit-pencil"
                ></icon>
                Edit
            </a>
        </template>

        <template slot="row" slot-scope="{item, index}">
            <td>
                <a :href="item.url" v-text="item.path" target="_blank"></a>
            </td>
            <td>
                <site-page-threshold-form :data-page="item"/>
            </td>
            <td>
                <icon
                    v-show="item.needs_review && !item.ignored"
                    class="mr-1"
                    data-icon-class="fill-current w-8 h-8 text-danger"
                    data-name="close-outline"
                ></icon>

                <icon
                    v-show="!item.needs_review && !item.ignored"
                    class="mr-1"
                    data-icon-class="fill-current w-8 h-8 text-success"
                    data-name="checkmark-outline"
                ></icon>
            </td>
            <td>
                <site-page-ignored-form :data-page="item"/>
            </td>
            <td>
                <span
                    class="status-pill"
                    :class="{
                        info: !item.processing,
                        warning: item.processing
                    }"
                    v-if="!item.ignored"
                    v-text="item.processing ? 'Processing' : 'Not Processing'"
                ></span>
            </td>
            <td>
                <a
                    v-if="!item.ignored"
                    :href="route('site.pages.show', item)"
                    class="button-link p-0 flex items-center"
                >
                    <icon
                        class="mr-1"
                        data-icon-class="fill-current w-3 h-3"
                        data-name="view-show"
                    ></icon>
                    <span class="text-sm">View</span>
                </a>
            </td>
        </template>
    </data-table>
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
        return {
            id: "site_page_data_table",
            site: this.dataSite,
            headers: [
                {
                    label: "Path",
                    column: "url"
                },
                {
                    label: "Threshold",
                    column: "difference_threshold"
                },
                {
                    label: "Within Threshold",
                    sortable: true,
                    column: "needs_review"
                },
                {
                    label: "Ignored",
                    column: "ignored"
                },
                {
                    label: "Snapshot Status",
                    column: "processing"
                },
                {
                    label: "",
                    sortable: false
                }
            ]
        };
    },

    computed: {},

    methods: {}
};
</script>
