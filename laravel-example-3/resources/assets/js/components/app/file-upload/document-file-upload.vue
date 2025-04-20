<template>
    <div class="document-file-upload">
        <!-- Upload -->
        <input-multi-file-upload
        name="files"
        :accepted-file-types="fileTypes"
        button-label="Add Files"
        :deleted-ids="deletedIds"
        :hide-dropzone="hideDropzone"
        :button-class="buttonClass"
        @file-uploaded="fileUploaded"
        :align-right="dataAlignRight"
        :data-max-size-mb="maxSizeMb"></input-multi-file-upload>

        <!-- Inputs -->
        <app-file-upload-submission-inputs
        data-collection="files"
        :data-deleted-files="deletedFiles"></app-file-upload-submission-inputs>
    </div>
</template>

<script>
let _filter = require("lodash.filter");
let _map = require("lodash.map");

module.exports = {
    name: "app-document-file-upload",

    props: {
        fileTypes: {
            type: Array,
            default() {
                return [
                    ".doc",
                    ".docx",
                    ".pdf",
                    ".xls",
                    ".xlsx",
                    ".jpg",
                    ".jpeg",
                    ".png",
                    ".bmp",
                    "pptx",
                    "ppt"
                ];
            }
        },

        hideDropzone: {
            type: Boolean,
            default: false
        },

        buttonClass: {
            type: String,
            default: "mn-width-175"
        },

        dataMaxSizeMb: {
            type: Number
        },

        dataAlignRight: {
            type: Boolean,
            default: true
        }
    },

    data() {
        return {
            deletedFiles: [],
            maxSizeMb: this.dataMaxSizeMb
        };
    },

    computed: {
        deletedIds() {
            return _map(this.deletedFiles, function(file) {
                return file.id;
            });
        }
    },

    methods: {
        fileUploaded(file) {
            window.Bus.$emit("document-file-list-uploaded", file);
        }
    },

    mounted() {
        window.Bus.$on("document-file-list-deleted", file => {
            this.deletedFiles.push(file);
        });
    }
};
</script>
