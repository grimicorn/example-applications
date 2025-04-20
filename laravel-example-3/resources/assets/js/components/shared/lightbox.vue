<template>
    <div
    @click="open"
    class="lightbox-wrap">
        <div class="lightbox-thumbnail-wrap">
            <slot name="thumbnail"></slot>
        </div>

        <modal
        class="modal-lightbox"
        :display-button="false"
        :auto-open="true"
        v-if="opened"
        @closed="close">
            <template>
                <div>
                    <loader :loading="true"></loader>
                    <img :src="src" :alt="alt" class="modal-lightbox-image">
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
export default {
    props: {
        dataSrc: {
            type: String,
            default: ""
        },
        dataAlt: {
            type: String,
            default: ""
        },
        dataOpened: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {};
    },

    computed: {
        src() {
            return this.dataSrc;
        },
        alt() {
            return this.dataAlt;
        },
        opened: {
            get() {
                return this.dataOpened;
            },
            set() {}
        }
    },

    methods: {
        open() {
            this.opened = true;
            this.$emit("opened");
        },
        close() {
            this.opened = false;
            this.$emit("closed");
        }
    },

    watch: {
        dataOpened(opened) {
            this.opened = opened;
        }
    }
};
</script>
