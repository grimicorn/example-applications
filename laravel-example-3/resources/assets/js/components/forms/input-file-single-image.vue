<template>
    <div
    class="fe-input-type-file-single-image-wrap"
    :class="inputWrapClass">
        <input-label
        :input-id="inputId"
        :label="inputLabel"
        :label-class="labelClass"
        :input-value="inputValue"
        :input-maxlength="inputMaxlength"></input-label>

        <avatar
        v-if="displayAvatar || currentImageSrc"
        :src="currentImageSrc"
        :width="currentImageWidth"
        :height="currentImageHeight"
        :display-initals="displayPhotoDefaultUserInitials"></avatar>

        <div
        class="fe-input-type-file-single-image-placeholder"
        v-else>
            Only {{ inputAccepts.split(',').join(', ') }} format supported.
        </div>

        <input
        type="file"
        :id="`${name}_file`"
        :value="fileValue"
        :name="`${name}_file`"
        class="fe-input-type-file"
        :class="inputClasses"
        :placeholder="inputPlaceholder"
        :disabled="inputDisabled"
        :readonly="inputReadonly"
        v-validate="fileValidationRules"
        @change="fileChange"
        :accept="inputAccepts">

        <label :for="`${name}_file`" class="btn btn-color4">Upload</label>

        <button
        type="button"
         class="btn btn-color6 single-image-delete-btn"
         @click="deleteFile"
         :disabled="!currentImageSrc">Delete</button>

        <input
        v-if="isDeleted"
        type="hidden"
        :name="`${name}_delete`"
        :id="`${name}_delete`"
        value="1">

        <input-error-message
        class="clear"
        :message="singleFileErrorMessage"></input-error-message>
    </div>
</template>

<script>
let _filter = require('lodash.filter');

module.exports = {
    mixins: [
        require('./../../mixins/confirm.js'),
        require('./../../mixins/input-mixin.js'),
        require('./../../mixins/input-file-size.js'),
    ],

    props: {
        imageSrc: {
            type: String,
            default: '',
        },

        imageWidth: {
            type: String,
            default: '',
        },

        imageHeight: {
            type: String,
            default: '',
        },

        inputAccepts: {
            type: String,
            default: '.jpg,.png,.jpeg',
        },

        displayPhotoDefaultUserInitials: {
            type: Boolean,
            default: false,
        },

        dataDisplayAvatar: {
            type: Boolean,
            default: true,
        },
    },

    data() {
        return {
            fileValue: '',
            inputValue: this.getImageSrc(),
            currentImageSrc: this.getImageSrc(),
            currentImageWidth: this.imageWidth,
            currentImageHeight: this.imageHeight,
            isDeleted: false,
            file: undefined,
            displayAvatar: this.dataDisplayAvatar,
        };
    },

    computed: {
        singleFileErrorMessage() {
            if (!this.fileErrorMessage) {
                return '';
            }

            let message = this.tweakSizeErrorMessage(this.fileErrorMessage);

            return message;
        },
    },

    methods: {
        tweakSizeErrorMessage(message) {
            // Standardize the file size error
            // Server: The file field may not be greater than 4 MB.
            // Client: The file field must be less than 4 MB.
            if (message.indexOf('may not be greater than') !== -1) {
                mesage.replace('may not be greater than', 'must be less than');
            }

            // We only want to effect the size error
            if (message.indexOf('must be less than') === -1) {
                return message;
            }

            return message.replace('file field', 'file');
        },

        getImageSrc() {
            let isDefault = this.imageSrc.indexOf('img/defaults') !== -1;

            return isDefault ? '' : this.imageSrc;
        },

        fileChange(event) {
            let target = event.target || event.srcElement;
            this.fileValue = target.value;
            this.file = target.files[0];
            this.isDeleted = target.value === '';
        },

        reset() {
            this.fileValue = '';
            this.file = undefined;
            this.$el.querySelector(`#${this.name}_file`).value = '';
            this.inputValue = '';
            this.errors.items = [];

            this.setCurrentImageAttributes(
                '',
                this.imageWidth,
                this.imageHeight
            );
        },

        setCurrentImageAttributes(src, width, height) {
            this.currentImageSrc = src;
            this.currentImageWidth = width;
            this.currentImageHeight = height;
        },

        setCurrentImageAttributesFromFile() {
            // Reset the current image attributes to the defaults.
            if (typeof this.file === 'undefined') {
                let src = this.isDeleted ? '' : this.imageSrc;
                this.setCurrentImageAttributes(
                    src,
                    this.imageWidth,
                    this.imageHeight
                );

                return;
            }

            // Read in the file as a data URL.
            let reader = new FileReader();
            reader.onload = e => {
                this.setCurrentImageAttributes(e.target.result, '', '');
            };
            reader.readAsDataURL(this.file);
        },

        deleteFile() {
            let id = 'input-file-single-image';
            let content =
                'Deleting your photo will remove it from your profile. You may upload another photo at any time.';

            this.confirm(id, content, () => {
                this.isDeleted = true;
                this.reset();
            });
        },
    },

    watch: {
        file() {
            this.setCurrentImageAttributesFromFile();
            this.emitChange();
        },
    },

    mounted() {
        this.handleFormValidationError(`${this.name}_file`);
    },
};
</script>
