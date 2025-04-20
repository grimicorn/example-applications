<template>
    <div
        class="notification flex items-center"
        v-if="display"
    >
        <ajax-form
            data-id="notification_read_form"
            data-method="PATCH"
            :data-action="readUrl"
            class="text-left flex-1 mr-4"
        >
            <template
                slot="submit"
                slot-scope="{submitDusk,disabled}"
            >
                <button
                    :dusk="submitDusk"
                    type="submit"
                    v-html="message"
                    :disabled="disabled"
                    class="button-link p-0 text-left font-normal"
                    :class="{
                        'button-muted' : read
                    }"
                ></button>
            </template>
        </ajax-form>

        <ajax-form
            data-id="notification_delete_form"
            data-method="DELETE"
            :data-action="deleteUrl"
            class="flex items-center"
            @submitted="deleted"
        >
            <template
                slot="submit"
                slot-scope="{submitDusk,disabled}"
            >
                <button
                    :dusk="submitDusk"
                    type="submit"
                    :disabled="disabled"
                    class="button-link p-0 text-left font-normal leading-0"
                >
                    <icon
                        data-name="close-outline"
                        data-icon-class="fill-current w-4 h-4"
                    ></icon>
                    <span class="sr-only">Delete</span>
                </button>
            </template>
        </ajax-form>
    </div>
</template>

<script>
export default {
    props: {
        dataNotification: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            notification: this.dataNotification,
            display: true
        };
    },

    computed: {
        readUrl() {
            return this.route("notifications.read.update", {
                notification: this.notification
            });
        },

        deleteUrl() {
            return this.route("notifications.destroy", {
                notification: this.notification
            });
        },

        message() {
            return this.notification.message;
        },

        link() {
            return this.notification.link;
        },

        read() {
            return this.notification.read_at;
        }
    },

    methods: {
        deleted() {
            this.display = false;
            this.$emit("deleted", this.notification);
        }
    }
};
</script>
