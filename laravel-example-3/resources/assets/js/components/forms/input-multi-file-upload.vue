<template>
    <form
    class="input-multi-file"
    :class="{
        'has-error': (this.hasErrors || this.extensionErrorMessages.length > 0),
        'mb3': !hideDropzone,
        'dropzone-not-available': dropzoneNotAvailable
    }">
        <!-- Limit Alert -->
        <alert
        type="warning"
        :timeout="5000"
        v-if="displayWarning"
        @closed="displayWarning = false">
            You have reached your maximum item upload limit.<br>Only up to {{ itemLimit }} items can be added to your account.
        </alert>

        <!-- Limit Message -->
        <span
        v-if="uploadLimitMessage"
        v-text="uploadLimitMessage"></span>

        <!-- Dropzone -->
        <div
        class="input-multi-file-dropzone mb1 pt1 pr2 pb1 pl2"
        :class="{
            'hide': hideDropzone,
        }"
        :id="dropzoneId">
            <strong
            class="input-multi-file-instructions block"
            v-if="instructions"
            v-text="instructions"></strong>

            <span
            class="input-multi-file-types-message block"
            v-if="supportedFileTypeMessage"
            v-text="supportedFileTypeMessage"></span>

            <span
            v-if="maxSizeMessage"
            class="input-multi-limit-message block"
            v-text="maxSizeMessage"></span>

            <input
            type="file"
            multiple="multiple"
            :allowed="allowedExtensions"
            @change="filesSelected"
            :disabled="isDisabled"
            v-validate="fileValidationRules">
        </div>

        <!-- Extension Error Message -->
        <input-error-message
        v-for="(extensionErrorMessage, index) in extensionErrorMessages"
        :key="index"
        :message="extensionErrorMessage"
        @dismissed="reset"
        :data-allow-dismiss="true"></input-error-message>

        <!-- Error Message -->
        <input-error-message
        :message="filesErrorMessage"
        v-if="hasErrors"
        @dismissed="reset"
        :data-allow-dismiss="true"></input-error-message>

        <!-- Open Upload -->
        <div :class="{
            'text-left': !alignRight,
            'text-right': alignRight,
        }">
            <button
            type="button"
            class="btn btn-input-multi-file"
            :class="buttonClass"
            v-text="buttonLabel"
            :disabled="isDisabled"
            @click="openFileDialog"></button>
        </div>
    </form>
</template>

<script>
let _map = require('lodash.map');
let _foreach = require('lodash.foreach');
let _filter = require('lodash.filter');
let moment = require('moment');

module.exports = {
    mixins: [
        require('./../../mixins/input-mixin.js'),
        require('./../../mixins/input-file-size.js'),
        require('./../../mixins/utilities.js'),
    ],

    props: {
        alignRight: {
            type: Boolean,
            default: true,
        },

        name: {
            type: String,
            required: true,
        },

        acceptedFileTypes: {
            type: Array,
            default() {
                return [];
            },
        },

        itemLimit: {
            type: Number,
            default: 0,
        },

        currentFileCount: {
            type: Number,
            default: 0,
        },

        buttonLabel: {
            type: String,
            default: 'Add Files',
        },

        dataExtraInstructions: {
            type: String,
            default: '',
        },

        deletedIds: {
            type: Array,
            default: function() {
                return [];
            },
        },

        hideDropzone: {
            type: Boolean,
            default: false,
        },

        buttonClass: {
            type: String,
            default: '',
        },
    },

    data() {
        return {
            files: [],
            uploadedFileCount: this.currentFileCount,
            isDisabled: false,
            displayWarning: false,
            extensionErrorMessages: [],
        };
    },

    computed: {
        instructions() {
            if (this.dropzoneNotAvailable) {
                return this.dataExtraInstructions;
            }

            return `Drag and drop files. ${this.dataExtraInstructions}`.trim();
        },

        dropzoneNotAvailable() {
            return this.isEdgeBrowser();
        },

        filesErrorMessage: {
            get() {
                let message = this.fileErrorMessage;
                if (!message) {
                    return '';
                }

                if (this.fileErrorMessage.indexOf('must be less than') !== -1) {
                    return `Uploaded files must be less than ${
                        this.maxSizeMb
                    } MB`;
                }

                return message;
            },

            set() {},
        },

        maxSizeMessage() {
            return `Maximum file size ${this.maxSizeMb}MB.`;
        },

        supportedFileTypeMessage() {
            if (this.acceptedFileTypes.length === 0) {
                return '';
            }

            let fileTypes = this.acceptedFileTypes.join(', ');

            return `Only ${fileTypes} format supported.`;
        },

        uploadLimitMessage() {
            if (this.uploadLimit > 0) {
                return `${this.currentFileCount} Photo(s) Uploaded (max. 8)`;
            }

            return '';
        },

        allowedExtensions() {
            let allowedExtensions = _map(this.acceptedFileTypes, function(
                fileType
            ) {
                return `.${fileType}`;
            }).join(',');

            return allowedExtensions ? allowedExtensions : '';
        },

        dropzoneId() {
            return `${this.name}_dropzone`;
        },

        inputId() {
            return `${this.name}_upload`;
        },

        deletedNewIndexes() {
            let indexes = _filter(this.deletedIds, id => {
                return id.toString().match('new-*') !== null;
            });

            return _map(indexes, function(index) {
                return parseInt(index.toString().replace('new-', ''), 10);
            });
        },

        fileInput() {
            return this.$el.querySelectorAll('input[type="file"]')[0];
        },
    },

    methods: {
        openFileDialog() {
            if (this.isDisabled) {
                return;
            }

            this.reset();
            this.fileInput.click();
        },

        filesSelected(evt) {
            // Reset the extension error message.
            this.extensionErrorMessages = [];

            // Add all of the files.
            _foreach(evt.target.files, file => this.fileSelected(file));
        },

        readFileAsDataUrl(file, callback) {
            let reader = new FileReader();
            reader.onload = e => callback(e.target.result);
            reader.readAsDataURL(file);
        },

        getFileAttributes(file, index) {
            return {
                collection_name: this.name,
                date: moment().format('M/D/YYYY'),
                file_name: file.name,
                id: `new-${index}`,
                mime_type: file.type,
                size: file.size,
            };
        },

        validateItemLimit(newFileCount = 1) {
            // If the default limit is set then we do not need to check it.
            if (this.itemLimit === 0) {
                return true;
            }

            // Track the uploaded file count.
            this.uploadedFileCount = this.uploadedFileCount + newFileCount;

            // Check the validation
            let validated = this.uploadedFileCount <= this.itemLimit;

            // Set the disabled state.
            this.isDisabled = this.uploadedFileCount >= this.itemLimit;

            // Alert the user if it is more than 1+ the item limit.
            if (this.uploadedFileCount >= this.itemLimit + 1) {
                this.displayWarning = true;
            }

            return validated;
        },

        validateExtension(file) {
            // If the allowed extensions are empty then we will accept anything.
            if (this.allowedExtensions.length === 0) {
                return;
            }

            // Get the files extension.
            let parts = file.name.split('.');
            let extension = parts[parts.length - 1].toString().toLowerCase();

            // Validate the extension.
            let isAllowed = this.allowedExtensions.indexOf(extension) !== -1;

            // Alert the user if invalid extension
            if (!isAllowed) {
                this.extensionErrorMessages.push(
                    `Files with extension .${extension} are not allowed.`
                );
            }

            return isAllowed;
        },

        validateFileSize(file) {
            if (this.maxSizeMb === undefined) {
                return true;
            }

            let maxBytes = this.maxSizeMb * 1024 * 1024;

            return parseInt(file.size, 10) <= maxBytes;
        },

        fileSelected(file) {
            // First we need to validate the file extension.
            if (!this.validateExtension(file)) {
                return;
            }

            // Then we need to validate the files item limit before adding the file.
            if (!this.validateItemLimit()) {
                return;
            }

            // Set the file to be uploaded.
            let uploadedFile = file;

            // Read in the file data URL and set it's attributes.
            this.readFileAsDataUrl(file, url => {
                // Validate the file size before accepting the file
                if (!this.validateFileSize(file)) {
                    return;
                }

                // Track the index so we will be able to ask for it to be deleted from files.
                let nextIndex = this.files.length;

                // Let the uploader know it will need to upload the file.
                uploadedFile.uploaded = false;

                // Add the file to the files that we will submit later.
                // This will not work without using the form.vue component as well.
                this.files.push(uploadedFile);

                // Add the files as the input value so we can access it from the parent form.vue component.
                // Since this will be what is included in the change event.
                this.inputValue = this.files;

                // Set the required attributes to "match" the DB model.
                let fileAttributes = this.getFileAttributes(file, nextIndex);
                fileAttributes.url = fileAttributes.full_url = url;

                // Let the world know that files have been uploaded
                this.$emit('file-uploaded', fileAttributes);
            });
        },

        reset() {
            this.hasErrors = false;
            this.extensionErrorMessages = [];
            this.filesErrorMessage = '';
            this.fileErrorMessage = '';
        },
    },

    mounted() {
        this.inputValue = [];

        // Set inital valdiation of the item limit
        this.validateItemLimit(0);
    },

    watch: {
        currentFileCount(value) {
            this.uploadedFileCount = value;

            // Re-validate the item limit to set disabled state and remove warning if needed.
            this.validateItemLimit(0);
        },

        deletedIds(deletedIds) {
            let newIds = _filter(deletedIds, deletedId => {
                return deletedId.toString().indexOf('new-') !== -1;
            });

            _foreach(newIds, newId => {
                let index = newId.toString().replace('new-', '');
                this.files[index] = false;
            });
        },
    },
};
</script>
