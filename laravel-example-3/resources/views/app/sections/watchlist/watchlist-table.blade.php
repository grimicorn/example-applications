<app-sort-table
:columns="{{ json_encode($columns) }}"
:paginated-items="{{ $paginatedWatchlists->toJson() }}"
:route="{{ json_encode(route('watchlists.index')) }}"
>
    <template slot="row" scope="props">
        <td class="pt2 pb2 wl-name">
            <a
            :href="props.item.show_url"
            class="a-hover-ul fc-color4"
            v-text="props.item.name"></a>
        </td>

        <td
        class="pt2 pb2 text-center wl-matches"
        v-text="props.item.match_count">
        </td>

        <td class="pt2 pb2 wl-actions">
            <a
            :href="props.item.show_url"
            class="btn btn-default mr2">
                View Results
            </a>

            <modal
            button-label="Edit Search"
            button-class="btn-color7"
            title="Edit Search"
            modal-class="modal-content-large"
            :display-cancel="true"
            :auto-open="{{ intval(old('watchlist_id')) }} == props.item.id">
                <div v-cloak>
                    <app-watchlist-form
                    :data-form-id="`watchlist_form_table_${props.index}`"
                    :is-edit="true"
                    :business-categories="props.item.business_categories"
                    :zipcode="props.item.zip_code"
                    :zipcode-radius="props.item.zip_code_radius"
                    :min-value="props.item.asking_price_min"
                    :max-value="props.item.asking_price_max"
                    :keyword="props.item.keyword"
                    :name="props.item.name"
                    :watchlist-id="props.item.id"></app-watchlist-form>
                </div>
            </modal>
        </td>
    </template>
</app-sort-table>
