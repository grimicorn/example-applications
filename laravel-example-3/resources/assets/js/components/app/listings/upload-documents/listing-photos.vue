<template>
    <div class="upload-documents-listing-photos">
        <h3 class="subhead flex mb3">
            <span class="flex-auto">Photos:</span>
            <span>{{ listingPhotos.length }} Photo(s) Uploaded (max. 8)</span>
        </h3>

        <!-- Upload -->
        <input-multi-file-upload
        name="photos"
        :accepted-file-types="['.jpg', '.png', '.jpeg']"
        :item-limit="8"
        :current-file-count="listingPhotos.length"
        button-label="Add Photos"
        button-class="mn-width-175"
        data-extra-instructions="Drag files below to reorder."
        :deleted-ids="deletedIds"
        @file-uploaded="photoUploaded"
        :data-max-size-mb="maxSizeMb"></input-multi-file-upload>

        <!-- Photos -->
        <div
        v-dragula="listingPhotos"
        drake="listing-photos"
        class="row">
            <listing-photo
            v-for="(photo, index) in listingPhotos"
            :key="photo.id"
            :photo="photo"
            :index="index"
            :data-max-size-mb="maxSizeMb"
            @deleted="deletePhoto"></listing-photo>
        </div>

        <!-- Inputs -->
        <app-file-upload-submission-inputs
        data-collection="photos"
        :data-deleted-files="deletedFiles"
        :data-ordered-files="listingPhotos"></app-file-upload-submission-inputs>
    </div>
</template>

<script>
let _filter = require('lodash.filter');
let _map = require('lodash.map');

module.exports = {
    mixins: [require('./../../../../mixins/confirm.js')],

    components: {
        'listing-photo': require('./listing-photo.vue'),
    },

    props: {
        listing: {
            type: Object,
            required: true,
        },

        photos: {
            type: Array,
            required: true,
        },
    },

    data() {
        return {
            listingPhotos: this.photos,
            deletedFiles: [],
            maxSizeMb: 4,
        };
    },

    computed: {
        deletedIds() {
            return _map(this.deletedFiles, function(file) {
                return file.id;
            });
        },
    },

    methods: {
        emitDeletedPhoto() {
            window.Bus.$emit('listing-photo:deleted');
            this.$emit('photo:deleted');
        },

        deletePhoto(photo) {
            let id = 'app-listing-photo';
            let content =
                'Are you sure you wanted to delete this photo? Once saved the photo will be permanently deleted.';

            this.confirm(id, content, () => {
                this.deletedFiles.push(photo);

                this.emitDeletedPhoto();

                this.listingPhotos = _filter(this.listingPhotos, function(
                    listingPhoto
                ) {
                    return listingPhoto.id !== photo.id;
                });
            });
        },

        photoUploaded(photo) {
            this.listingPhotos.push(photo);
        },
    },

    created() {
        // Configure Dragula service.
        Vue.$dragula.$service.options('listing-photos', {
            moves: function(el, source, handle, sibling) {
                // Only allow elements with the js-drag-handle class.
                let isValid = handle.classList.contains('js-drag-handle');
                let parentIsValid = handle.parentNode.classList.contains(
                    'js-drag-handle'
                );

                return isValid || parentIsValid;
            },
        });
    },
};
</script>
