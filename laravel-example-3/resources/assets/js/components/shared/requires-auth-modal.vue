<template>
    <modal
    v-if="open"
    title="User Account Required"
    :auto-open="true"
    @closed="open = false">
        <div v-cloak>
            <div class="text-center">
                <a href="/login" class="btn btn-color5 mr1">Login</a>
                <a href="/register" class="btn btn-color4">Join Now</a>
            </div>
        </div>
    </modal>
</template>

<script>
module.exports = {
    props: {},

    data() {
        return {
            open: false
        };
    },

    computed: {},

    methods: {},

    mounted() {
        window.Bus.$on("requires-auth-modal:open", () => {
            this.open = true;
        });

        window.Bus.$on("requires-auth-modal:close", () => {
            this.open = false;
        });
    }
};
</script>
