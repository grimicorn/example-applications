@extends('layouts.application')

@section('content')

<app-sort-table
:columns="{{ json_encode($columns) }}"
:paginated-items="{{ $paginatedListings->toJson() }}"
:route="{{ json_encode(route('listing.index')) }}"
>
    <template slot="header_left" scope="props">
        <a href="{{ route('listing.create') }}" class="btn">Add New Business</a>
    </template>

    <template slot="header_right" scope="props">
        <app-sort-table-search
        @submit="props.vm.search"></app-sort-table-search>
    </template>

    <template slot="subheader" scope="props">
        @php
            $publishedListings = DB::select('select * from listings where user_id = ' . $currentUser->id . ' and published = 1', [1]);
        @endphp
        @if($currentUser->current_billing_plan === "monthly-99" && count($publishedListings) === 3)
            Output text for users with small plan.
        @endif
    </template>

    <template slot="row" scope="props">
            <td width="40%">
                <a
                class="a-hover-ul fc-color4"
                :href="props.item.edit_url" v-text="props.item.business_name"></a>
            </td>

            <td width="15%"
            v-text="props.formatDate(props.item.created_at)"></td>

            <td width="10%"
            v-text="props.item.published ? 'Published' : 'Draft'"></td>

            <td width="15%" >
                <a
                v-if="props.item.published"
                :href="props.item.show_url"
                target="_blank"
                v-text="'View Page'"
                class="btn btn-color5 width-100"></a>

                <a
                v-else
                :href="`/dashboard/listing/${props.item.id}/preview`"
                target="_blank"
                v-text="'Preview'"
                class="btn btn-color5 width-100"></a>
            </td>

            <td width="10%">
                <a
                :href="props.item.edit_url"
                class="btn btn-color6">Edit</a>
            </td>
    </template>
</app-sort-table>
@endsection
