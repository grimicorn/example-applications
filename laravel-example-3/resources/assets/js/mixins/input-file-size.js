let _filter = require('lodash.filter');

module.exports = {
    props: {
        dataMaxSizeMb: {
            type: Number,
        },
    },

    data() {
        return {
            maxSizeMb: this.dataMaxSizeMb,
        };
    },

    computed: {
        maxSizeKb() {
            let maxSizeMb = parseInt(this.maxSizeMb, 10);
            if (isNaN(maxSizeMb)) {
                return undefined;
            }

            return maxSizeMb * 1024;
        },

        fileErrorMessage: {
            get() {
                let message = this.errorMessage;

                if (!message) {
                    return '';
                }

                message = message.replace(
                    `${this.maxSizeKb} KB`,
                    `${this.maxSizeMb} MB`
                );

                message = message.replace(
                    `${this.maxSizeKb} kilobytes`,
                    `${this.maxSizeMb} MB`
                );

                return message;
            },

            set() {},
        },

        fileValidationRules() {
            let rules = this.validationRules.split('|');

            if (this.maxSizeKb) {
                rules.push(`size:${this.maxSizeKb}`);
            }

            rules = _filter(rules, function(rule) {
                return rule;
            });

            return rules.join('|');
        },
    },

    methods: {
        openFileDialog() {
            if (this.isDisabled) {
                return;
            }

            this.$el.querySelectorAll('input[type="file"]')[0].click();
        },

        filesSelected(evt) {
            // Reset the extension error message.
            this.extensionErrorMessages = [];

            // Add all of the files.
            _foreach(evt.target.files, (file) => this.fileSelected(file));
        },

        readFileAsDataUrl(file, callback) {
            let reader = new FileReader();
            reader.onload = (e) => callback(e.target.result);
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
            let extension = parts[parts.length - 1];

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

        fileSelected(file) {
            // First we need to validate the file extension.
            if (!this.validateExtension(file)) {
                return;
            }

            // Then we need to validate the files item limit before adding the file.
            if (!this.validateItemLimit()) {
                return;
            }

            // Track the index so we will be able to ask for it to be deleted from files.
            let nextIndex = this.files.length;

            // Let the uploader know it will need to upload the file.
            file.uploaded = false;

            // Add the file to the files that we will submit later.
            // This will not work without using the form.vue component as well.
            this.files.push(file);

            // Add the files as the input value so we can access it from the parent form.vue component.
            // Since this will be what is included in the change event.
            this.inputValue = this.files;

            // Read in the file data URL and set it's attributes.
            this.readFileAsDataUrl(file, (url) => {
                // Set the required attributes to "match" the DB model.
                let fileAttributes = this.getFileAttributes(file, nextIndex);
                fileAttributes.url = fileAttributes.full_url = url;

                // Let the world know that files have been uploaded
                this.$emit('file-uploaded', fileAttributes);
            });
        },
    },
};
