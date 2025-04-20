<template>
    <app-form-accordion
    header-title="Documents"
    :collapsible="false">
        <template slot="header-right">
            <input-search
            @submit="search"
            class="mb0 pull-right width-auto"
            v-model="keyword"></input-search>
        </template>

        <template slot="content">
            <app-document-list
            v-if="documents"
            :files="documents"
            :remove-borders-and-striping="true"
            :display-owner="true"
            :delete-url="deleteUrl"
            :allow-search="true"></app-document-list>

            <div
            class="flex justify-center"
            v-else>
                <strong>No Documents found</strong>
            </div>
        </template>
    </app-form-accordion>
</template>

<script>
let _filter = require("lodash.filter");

module.exports = {
    props: {
        dataDocuments: {
            type: [Array, Object],
            default() {
                return [];
            }
        },

        deleteUrl: {
            type: String,
            default: ""
        },

        dataMaxSizeMb: {
            type: Number
        }
    },

    data() {
        return {
            documents: this.dataDocuments,
            keyword: "",
            maxSizeMb: this.dataMaxSizeMb
        };
    },

    computed: {},

    methods: {
        search(keyword) {
            this.keyword = keyword;

            if (this.keyword === "") {
                this.documents = this.dataDocuments;
                return;
            }

            this.documents = _filter(this.documents, document => {
                let filename = document.file_name.toLowerCase();
                let keyword = this.keyword.toLowerCase();

                return filename.includes(keyword);
            });
        }
    },

    watch: {
        keyword(value) {
            if (value === "") {
                this.documents = this.dataDocuments;
            }
        }
    }
};
</script>
