<template>
    <div
    class="listing-photo col-xs-3 mb3"
    :class="{
        'has-error': hasError
    }">
        <div
        class="listing-photo-header flex js-drag-handle">
            <span v-text="index + 1" class="listing-photo-number"></span>
            <span class="flex-auto overflow-hidden">/////////////////////</span>
            <span
                class="listing-photo-delete"
                @click.stop="deletePhoto"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
        </div>

        <div class="listing-photo-wrap">
            <img
            v-if="photo.upload_url"
            :src="photo.upload_url">

            <img
            v-else
            :src="photo.full_url">

            <input-error-message
            class="clear"
            :message="photoErrorMessage"></input-error-message>
        </div>

        <!-- This allows easy access to the parent form and validation -->
        <input type="hidden">
    </div>
</template>

<script>
module.exports = {
    props: {
        photo: {
            type: Object,
            required: true
        },

        index: {
            type: Number,
            required: true
        },

        dataMaxSizeMb: {
            type: Number,
            default: 4
        }
    },

    data() {
        return {
            maxSizeMb: this.dataMaxSizeMb
        };
    },

    computed: {
        hasError() {
            return this.toLarge;
        },

        toLarge() {
            let maxBytes = this.maxSizeMb * 1024 * 1024;

            return parseInt(this.photo.size, 10) > maxBytes;
        },

        photoErrorMessage() {
            if (this.toLarge) {
                return `Max size ${this.maxSizeMb} MB`;
            }

            return "";
        },

        validationInput() {
            return this.$el.querySelector("input");
        },

        formId() {
            return this.validationInput.form.id;
        }
    },

    methods: {
        deletePhoto() {
            this.$emit("deleted", this.photo);
        }
    }
};
</script>
