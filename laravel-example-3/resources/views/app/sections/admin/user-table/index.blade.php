@extends('layouts.application')

@section('content')
<app-sort-table
class="user-table-wrap"
:columns="{{ json_encode($columns) }}"
:paginated-items="{{ $paginatedUsers->toJson() }}"
:route="{{ json_encode(route('admin.user-table')) }}"
>
    <template slot="header_left" scope="props">
        <app-sort-table-search></app-sort-table-search>
    </template>

    <template slot="header_right" scope="props">
        <app-sort-table-export-button
        :route="{{ json_encode(route('admin.user-filter.export-csv')) }}"
        :filters="props.vm.filters"></app-sort-table-export-button>
    </template>

    <template slot="row" scope="props">
            <td class="has-actions">
                <span
                v-text="`${props.item.last_name}, ${props.item.first_name}`"
                class="block"></span>

                <div
                class="action-items"
                v-if="{{ $currentUser->id }} !== props.item.id">
                    <a
                    class="action-item"
                    :href="`/spark/kiosk/users/impersonate/${props.item.id}`">Switch User</a>
                    <span class="action-item-seperator"> | </span>
                    <a
                    class="action-item"
                    :href="`/professional/${props.item.id}`">Profile</a>

                    <span class="action-item-seperator"> | </span>

                    <app-admin-delete-user
                    :data-user-id="props.item.id"></app-admin-delete-user>
                </div>
            </td>

            <td v-text="props.item.primary_roles ? props.item.primary_roles.join(', ') : ''"></td>

            <td class="user-table-email" v-text="props.item.email"></td>

            <td class="text-center">
                <app-profile-account-balance
                :user-id="props.item.id"></app-profile-account-balance>
            </td>

            <td
            class="text-center"
            v-text="props.item.account_status_label"></td>

            <td v-text="props.item.active_listings_count" class="text-center"></td>

            <td class="text-center">
                <timezone-date :date="props.item.last_login_string"></timezone-date>
            </td>
    </template>
</app-sort-table>
@endsection
