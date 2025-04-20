<template>
    <div
        class="block flex items-center justify-center"
    >
        <a
            :href="link"
            class="no-underline text-white flex"
        >
            &lt;
            <span class="font-black text-accent">src</span>
            <span class="font-normal">Watch</span>
            /&gt;
        </a>
    </div>
</template>

<script>
export default {
    props: {
        dataLink: {
            type: String
        }
    },

    data() {
        return {};
    },

    computed: {
        link() {
            if (this.dataLink) {
                return this.dataLink;
            }

            return "/";
        }
    },

    methods: {}
};
</script>
