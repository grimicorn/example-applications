<template>
    <div
    class="row flex-nowrap listing-card"
    :class="{
        'bg-color2': even,
        'bg-color12': !even
    }">
        <div class="col-md-3">
            <div class="listing-card-img">
                <a
                :href="listing.show_url"
                class="listing-photo block bg-color12">
                    <img
                    class="img-responsive"
                    :src="listing.cover_photo_favorite_thumbnail_url"
                    :alt="`${listing.title} image`">
                </a>
            </div>
            <div class="text-center flex flex-column items-center listing-card-lcs">
                <div class="fc-color4 text-bold pb1">
                    LC Rating:

                    <lc-rating-tooltip data-icon-class="fc-color5"></lc-rating-tooltip>
                </div>
                <lc-rating
                class="fc-color5 fz-24"
                :data-enable-label="false"
                :data-percentage-complete="score"></lc-rating>
            </div>
        </div>

        <div class="col-sm-9">
            <div class="row">
                <div class="col-sm-7">
                    <div class="listing-card-content">
                        <a
                        :href="listing.show_url"
                        class="fz-24 fc-color4 a-nd"
                        v-text="listing.title"></a>

                        <div
                        class="fz-16"
                        v-if="listing.address"
                        v-text="listing.address"></div>

                        <div
                        class="fc-color5 text-bold fz-20 pb1"
                        v-if="listing.asking_price">
                            {{ listing.asking_price | price }}
                        </div>

                        <div
                        class="lh-copy"
                        v-if="listing.summary_business_description"
                        v-text="listing.summary_business_description"></div>
                    </div>
                </div>
            <div class="col-sm-5">
                <div
                class="row"
                v-if="!listing.current_user_is_owner">
                    <div class="pb2 col-xs-10">
                        <app-create-inquiry :listing="listing"></app-create-inquiry>
                    </div>
                    <div
                    class="pt1 pb1 col-xs-2">
                        <favorite-listing-link
                        class="pull-right text clear"
                        :listing-id="listing.id"
                        :favorite-id="favoriteId"
                        @unfavorited="unfavorited"></favorite-listing-link>
                    </div>
                </div>


                <div
                class="overflow-hidden clear"
                :class="{
                    'bb1' : even,
                    'bb1-color10' : !even,
                }">
                    <div class="pb1 pt1 text-bold pull-left">
                        Revenue:
                    </div>
                    <div
                    class="pb1 pt1 fc-color5 text-bold pull-right">
                        {{ listing.revenue | price }}
                    </div>
                </div>

                <div
                class="overflow-hidden clear"
                :class="{
                    'bb1' : even,
                    'bb1-color10' : !even,
                }">
                    <div class="pb1 pt1 text-bold pull-left">
                        EBITDA:
                    </div>
                    <div
                    class="pb1 pt1 fc-color5 text-bold pull-right">
                        {{ listing.ebitda | price }}
                    </div>
                </div>

                <div
                class="overflow-hidden clear"
                :class="{
                    'bb1' : even,
                    'bb1-color10' : !even,
                }">
                    <div class="pb1 pt1 text-bold pull-left">
                        Pre-Tax Income:
                    </div>
                    <div
                    class="pb1 pt1 fc-color5 text-bold pull-right">
                        {{ listing.pre_tax_earnings | price }}
                    </div>
                </div>

                <div
                class="overflow-hidden clear"
                :class="{
                    'bb1' : even,
                    'bb1-color10' : !even,
                }">
                    <div class="pb1 pt1 text-bold pull-left">
                        Discretionary Cash Flow:
                    </div>
                    <div
                    class="pb1 pt1 fc-color5 text-bold pull-right">
                        {{ listing.discretionary_cash_flow | price }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

</template>

<script>
let filters = require('./../../../mixins/filters.js');

module.exports = {
    mixins: [filters],

    props: {
        listing: {
            type: Object,
            required: true,
        },

        even: {
            type: Boolean,
            required: true,
        },

        score: {
            type: Number,
            default: 0,
        },

        userExchangeSpace: {
            type: Object,
            default: null,
        },
    },

    data() {
        return {
            isFavorited: true,
        };
    },

    computed: {
        favoriteId: {
            get() {
                return this.listing.current_user_favorite_id;
            },

            set() {},
        },

        loggedIn() {
            return window.Spark.userId !== null;
        },
    },

    methods: {
        unfavorited() {
            this.$emit('unfavorited', this.favoriteId);

            this.favoriteId = 0;
            this.isFavorited = false;
        },
    },
};
</script>
