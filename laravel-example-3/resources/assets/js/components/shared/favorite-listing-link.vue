<template>
    <span
    :title="titleAttr"
    @click="handleClick"
    class="pointer"
    :class="{
        'fc-color3' : !isFavorited,
        'fc-color4' : isFavorited,
    }">
        <i
        class="fa fc-color4 fa-2x"
        :class="{
            'fa-heart': isFavorited,
            'fa-heart-o': !isFavorited,
        }" aria-hidden="true"></i>
    </span>
</template>

<script>
module.exports = {
    props: {
        listingId: {
            type: Number,
            required: true
        },

        favoriteId: {
            type: Number,
            default: 0
        }
    },

    data() {
        return {
            isFavorited: this.favoriteId > 0,
            favoriteRoute: "/dashboard/favorites/store",
            favId: this.favoriteId
        };
    },

    computed: {
        unfavoriteRoute() {
            return `/dashboard/favorites/${this.favId}/destroy`;
        },

        loggedIn() {
            return window.Spark.userId !== null;
        },

        titleAttr() {
            if (this.isFavorited) {
                return "Remove from Favorites";
            }

            return "Add to Favorites";
        }
    },

    methods: {
        handleClick() {
            if (!this.loggedIn) {
                window.Bus.$emit("requires-auth-modal:open");
                return;
            }

            if (this.isFavorited) {
                this.unfavorite();
            } else {
                this.favorite();
            }
        },

        favorite() {
            window.axios
                .post(this.favoriteRoute, {
                    listing_id: this.listingId
                })
                .then(response => {
                    this.favId = response.data.favorite_id;
                    this.isFavorited = true;
                    this.$emit("favorited");
                });
        },

        unfavorite() {
            window.axios.delete(this.unfavoriteRoute).then(response => {
                this.favId = 0;
                this.isFavorited = false;
                this.$emit("unfavorited");
            });
        }
    }
};
</script>
