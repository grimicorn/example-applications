<template>
    <div>
        <div
        class="favorite-card-filters">
            <div class="flex-auto">
                <input-select
                name="filter_favorites"
                class="inline-block width-auto"
                v-model="sortKey"
                :options="filterOptions"></input-select>
            </div>
            <div>
                <input-search
                @submit="(value) => keyword = value"></input-search>
            </div>
        </div>

        <div v-if="loading" class="relative" style="min-height: 58px;">
            <loader :loading="loading"></loader>
        </div>

        <div v-else>
            <div
            v-if="favorites && favorites.length > 0"
            class="favorite-cards container">
                <app-listing-card
                v-for="(favorite, index) in favorites"
                :key="favorite.id"
                :listing="favorite"
                :even="(index + 1) % 2 === 0"
                @unfavorited="unfavorited"
                :score="favorite.current_score_total_percentage"></app-listing-card>

                <model-pagination
                :paginated="paginatedFavorites"
                :allow-navigation="true"></model-pagination>
            </div>

            <div
            class="pt2"
            v-else-if="keyword">
                No favorites found for "{{ keyword }}".
            </div>

            <p
            class="pt2 mt2 mb2 ml2 mr2 text-center"
            v-else>
                You currently do not have any business listings in your Favorites list.<br>
                Click the heart icon on any business listing to add them here.<br>
                <a href="/businesses">Search businesses-for-sale</a>
            </p>
        </div>
    </div>
</template>

<script>
let _filter = require("lodash.filter");

module.exports = {
    props: {
        paginatedFavorites: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            favorites: this.paginatedFavorites.data,
            sortKey: "lcs_high_to_low",
            keyword: "",
            loading: false
        };
    },

    computed: {
        filterOptions() {
            return [
                {
                    value: "lcs_high_to_low",
                    label: "Listing Completion Score"
                },
                {
                    value: "asking_price_low_to_high",
                    label: "Asking Price Low-High"
                },
                {
                    value: "asking_price_high_to_low",
                    label: "Asking Price High-Low"
                }
            ];
        },

        updateUrl() {
            return `${window.location.origin}${window.location.pathname}`;
        }
    },

    methods: {
        unfavorited(favoriteId) {
            this.favorites = _filter(this.favorites, favorite => {
                return favorite.current_user_favorite_id !== favoriteId;
            });
        },

        update() {
            let filters = {
                sortKey: this.sortKey,
                keyword: this.keyword
            };

            this.loading = true;

            window.axios
                .post(this.updateUrl, filters)
                .then(({ data }) => {
                    this.favorites = data.data;
                    this.loading = false;
                })
                .catch(() => (this.loading = false));
        }
    },

    watch: {
        sortKey(value) {
            this.update();
        },

        keyword(value) {
            this.update();
        }
    }
};
</script>
