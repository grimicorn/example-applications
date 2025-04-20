<template>
    <div class="listing-document-inputs">
        <input-hidden
        v-for="deletedId in deletedIds"
        :name="`${collection}[deleted][]`"
        :value="deletedId"
        :key="deletedId"></input-hidden>

        <input-hidden
        :name="`file_upload_submission_inputs_update`"
        :value="deletedIds.concat(orderedIds).join(',')"></input-hidden>

        <input-hidden
        v-for="orderedId in orderedIds"
        :name="`${collection}[order][]`"
        :value="orderedId"
        :key="orderedId"></input-hidden>

        <input-hidden
        v-for="orderedFile in orderedFiles"
        :name="`${collection}[order-file-names][${orderedFile.id}]`"
        :value="orderedFile.file_name"
        :key="orderedFile.id"></input-hidden>
    </div>
</template>

<script>
let _map = require("lodash.map");

module.exports = {
    name: "app-file-upload-submission-inputs",

    props: {
        dataCollection: {
            type: String,
            required: true
        },

        dataDeletedFiles: {
            type: Array,
            default: function() {
                return [];
            }
        },

        dataOrderedFiles: {
            type: Array,
            default: function() {
                return [];
            }
        }
    },

    data() {
        return {};
    },

    computed: {
        collection() {
            return this.dataCollection;
        },

        deletedFiles() {
            return this.dataDeletedFiles;
        },

        orderedFiles() {
            return this.dataOrderedFiles;
        },

        orderedIds() {
            return _map(this.orderedFiles, function(file) {
                return file.id;
            });
        },

        deletedIds() {
            return _map(this.deletedFiles, function(file) {
                return file.id;
            });
        }
    },

    methods: {}
};
</script>
