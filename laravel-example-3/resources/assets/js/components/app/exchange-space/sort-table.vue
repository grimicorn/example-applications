<template>
    <app-sort-table
    :columns="columns"
    :paginated-items="paginatedItems"
    :route="route"
    :data-filters="dataFilters">
        <template slot="header_left" scope="props">
            <input-select
            name="listing_id"
            class="width-auto mx-width-35p mb0 inline-block"
            :options="listingOptions"
            placeholder="Select Business"
            v-model="dataFilters.listing_id"
            :input-disabled="props.vm.isFiltering"
            @change="handleListingFilter(props)"></input-select>
        </template>

        <template slot="header_right" scope="props">
            <app-sort-table-search
            @submit="props.vm.search"></app-sort-table-search>
        </template>

        <template slot="row" scope="props">
            <td width="15%" class="pr2 pt2 pb2 text-center">
                <app-exchange-space-add-to-dashboard
                :space="props.item"></app-exchange-space-add-to-dashboard>
            </td>

            <td width="40%"
            class="pt2 pb2">
                <app-edit-subtitle
                :route="props.item.title_edit_url"
                :subtitle="props.item.title"
                :is-inline="true">
                    <template scope="linkProps">
                        <a
                        :href="props.item.show_url"
                        class="a-hover-ul fc-color4"
                        v-text="linkProps.editTitle"></a>
                    </template>
                </app-edit-subtitle>
            </td>

            <td width="15%" class="pt2 pb2">
                <app-exchange-space-deal-status
                :space="props.item"></app-exchange-space-deal-status>
            </td>

            <td width="15%" class="pt2 pb2 text-center"
            v-text="props.formatDate(props.item.updated_at)"></td>

            <td width="15%" class="pt2 pb2 text-center">
                <a
                :href="`/dashboard/exchange-spaces/${props.item.id}/notifications?unread=1`"
                v-if="props.item.notification_count > 0"
                class="icon-count a-nd a-color2"
                v-text="props.item.notification_count"></a>

                <span
                v-else
                class="icon-count"
                v-text="props.item.notification_count"></span>
            </td>
        </template>
    </app-sort-table>
</template>

<script>
module.exports = {
    props: {
        columns: {
            type: Array,
            default() {
                return [];
            },
        },

        paginatedItems: {
            type: Object,
            required: true,
        },

        route: {
            type: String,
            required: true,
        },

        listingOptions: {
            type: [Array, Object],
            default() {
                return [];
            },
        },
    },

    data() {
        return {
            dataFilters: {
                listing_id: '',
            },
        };
    },

    computed: {},

    methods: {
        handleListingFilter(props) {
            window.Vue.nextTick(() => {
                props.vm.filter();
            });
        },
    },
};
</script>
