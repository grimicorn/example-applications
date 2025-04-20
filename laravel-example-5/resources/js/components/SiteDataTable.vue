<template>
    <data-table
        :data-headers="headers"
        :data-url="route('sites.index')"
        :data-id="id"
        data-item-model="Site"
    >
        <template slot="toolbar">
            <a
                :href="route('sites.create')"
                class="button"
            >Add New</a>
        </template>

        <template slot="row" slot-scope="{item, index}">
            <td>
                <a
                    :href="route('sites.show', {site: item})"
                    v-text="item.name"
                ></a>
            </td>
            <td v-text="`${item.difference_threshold * 100}%`"></td>
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
                <ul class="list-reset flex items-center">
                    <!-- View -->
                    <li class="mx-2">
                        <a
                            :href="route('sites.show', {site: item})"
                            class="button-link p-0 flex items-center"
                        >
                            <icon
                                class="mr-1"
                                data-icon-class="fill-current w-3 h-3"
                                data-name="view-show"
                            ></icon>
                            <span class="text-sm">View</span>
                        </a>
                    </li>

                    <!-- Edit -->
                    <li class="mx-2">
                        <a
                            :href="route('sites.edit', {site: item})"
                            class="button-link p-0 flex items-center"
                        >
                            <icon
                                class="mr-1"
                                data-icon-class="fill-current w-3 h-3"
                                data-name="edit-pencil"
                            ></icon>
                            <span class="text-sm">Edit</span>
                        </a>
                    </li>

                    <!-- Delete -->
                    <li class="mx-2">
                        <ajax-form
                            :data-id="`delete_site_${item.id}`"
                            :data-confirmation='`Are you sure you want to delete your site "${item.name}"?`'
                            data-method="DELETE"
                            :data-action="route('sites.destroy', {site: item})"
                            @submitted="deleteFromDataTable(item)"
                        >
                            <template slot="submit" slot-scope="{disabled, submitDusk}">
                                <button
                                    class="button-link p-0 flex items-center"
                                    :dusk="submitDusk"
                                    type="submit"
                                    :disabled="disabled"
                                >
                                    <icon
                                        class="mr-1"
                                        data-icon-class="fill-current w-3 h-3"
                                        data-name="close-outline"
                                    ></icon>
                                    <span class="text-sm">Delete</span>
                                </button>
                            </template>
                        </ajax-form>
                    </li>
                </ul>
            </td>
        </template>
    </data-table>
</template>

<script>
export default {
    props: {},

    data() {
        return {
            id: "site_data_table",

            headers: [
                {
                    label: "Name"
                },
                {
                    label: "Threshold",
                    column: "difference_threshold"
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

    methods: {
        deleteFromDataTable(item) {
            window.Bus.$emit(`data-table.${this.id}.item.remove`, item);
        }
    }
};
</script>
