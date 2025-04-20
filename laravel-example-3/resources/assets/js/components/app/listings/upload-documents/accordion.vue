<template>
    <app-form-accordion
    header-title="Business Page Documents"
    class="listing-details-upload-documents"
    :default-open="true">

        <div slot="content" class="mb3">
            <strong class="block mb3">Photos and documents uploaded here are attached to your Business Page. If you don't upload photo(s) a default image will be shown based on the business category and sub-category selected. The file upload section is a great place to include an NDA or Financial Disclosure form.</strong>

            <listing-photos :listing="listing" :photos="photos"></listing-photos>

            <h3 class="subhead flex mb1">Files:</h3>

            <app-document-file-upload
            :data-max-size-mb="5"></app-document-file-upload>

            <app-document-list
            :data-max-size-mb="5"
            :files="files"></app-document-list>
        </div>
    </app-form-accordion>
</template>

<script>
module.exports = {
    components: {
        "listing-photos": require("./listing-photos.vue")
    },

    props: {
        dataListing: {
            type: Object,
            required: true
        },

        photos: {
            type: Array,
            required: true
        },

        files: {
            type: Array,
            required: true
        },

        formId: {
            type: String,
            default: ""
        },

        percentageComplete: {
            type: Number,
            default: 0
        }
    },

    data() {
        return {
            listing: this.dataListing
        };
    },

    computed: {},

    methods: {}
};
</script>
