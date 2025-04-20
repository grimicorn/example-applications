<template>
    <!-- Files -->
    <table
    class="sort-table"
    :class="{
        'remove-borders-and-striping': removeBordersAndStriping,
    }"
    v-if="this.documents.length > 0">
        <thead
        v-if="!removeHeader">
            <tr>
                <th
                @click="sortBy('file_name')">
                    <span class="sort-table-label">File Name</span>

                    <app-sort-table-order-icons
                    :is-sorted="fileNameSorted"
                    :sort-order="sortOrder"></app-sort-table-order-icons>
                </th>

                <th
                @click="sortBy('owner_name')"
                v-if="displayOwner">
                    <span class="sort-table-label">File Owner</span>

                    <app-sort-table-order-icons
                    :is-sorted="ownerNameSorted"
                    :sort-order="sortOrder"></app-sort-table-order-icons>
                </th>

                <th
                @click="sortBy('date')">
                    <span class="sort-table-label">Date</span>

                    <app-sort-table-order-icons
                    :is-sorted="dateSorted"
                    :sort-order="sortOrder"></app-sort-table-order-icons>
                </th>

                <th>
                    <span class="sort-table-label">Delete</span>
                </th>
            </tr>
        </thead>

        <tbody>
            <tr
            v-for="file in this.documents"
            :key="file.full_url">
                <!-- File Name -->
                <td class="attached-file">
                   <app-file-preview-link
                   :label="file.file_name"
                   :mime-type="file.mime_type"
                   :url="file.full_url"></app-file-preview-link>
                </td>

                <!-- File Owner -->
                <td
                v-if="displayOwner"
                v-text="file.owner_name"></td>

                <!-- Date -->
                <td
                v-text="file.date"></td>

                <!-- Delete -->
                <td class="text-center">
                    <i
                    class="fa fa-times-circle pointer"
                    aria-hidden="true"
                    v-if="!file.undeletable"
                    @click="handleDeleteFile(file)"></i>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    let _filter = require('lodash.filter');
    let _sortby = require('lodash.sortby');

    module.exports = {
        name: 'app-document-file-list',

        mixins: [
            require('./../../../mixins/confirm.js'),
        ],

        props: {
            files: {
                type: Array,
                default() {
                    return [];
                },
            },

            removeBordersAndStriping: {
                type: Boolean,
                default: false,
            },

            removeHeader: {
                type: Boolean,
                default: false,
            },

            displayOwner: {
                type: Boolean,
                default: false,
            },

            deleteUrl: {
                type: String,
                default: '',
            },
        },

        data() {
            return {
                sortKey: 'date',
                sortReverse: false,
                documents: this.files,
            };
        },

        computed: {
            sortOrder() {
                return this.sortReverse ? 'desc' : 'asc';
            },

            ownerNameSorted() {
                return this.sortKey === 'owner_name';
            },

            fileNameSorted() {
                return this.sortKey === 'file_name';
            },

            dateSorted() {
                return this.sortKey === 'date';
            },

            confirmContent() {
                let autoMessage = 'Are you sure you wanted to delete this file? The file will be permanently deleted.';
                let submitMssage = 'Are you sure you wanted to delete this file? Once saved the file will be permanently deleted.';

                return (this.deleteUrl === '') ? submitMssage : autoMessage;
            },
        },

        methods: {
            sortBy(sortKey) {
                this.sortReverse = (this.sortKey === sortKey) ? !this.sortReverse : false;
                this.sortKey = sortKey;

                // Sort the files
                this.documents = _sortby(this.documents, [sortKey]);

                // Reverse the files if required.
                if (this.sortReverse) {
                    this.documents.reverse();
                }
            },

            deleteFile(file) {
                window.axios
                .post(this.deleteUrl, {'media_id': file.id})
                .then((response) => {
                    this.afterFileDeletion(file);
                });
            },

            afterFileDeletion(file) {
                window.Bus.$emit('document-file-list-deleted', file);

                this.documents = _filter(this.documents, function(document) {
                    return document.id !== file.id;
                });
            },

            handleDeleteFile(file) {
                this.confirm(
                    'app-document-file-list',
                    this.confirmContent,
                    (confirmation) => {
                        if (this.deleteUrl) {
                            this.deleteFile(file);
                        } else {
                            this.afterFileDeletion(file);
                        }
                    }
                );
            },
        },

        mounted() {
            window.Bus.$on('document-file-list-uploaded', (file) => {
                this.documents.push(file);
            });
        },

        watch: {
            files() {
                this.documents = this.files;
            }
        },
    };
</script>
