<!--this is currently used in the diligence center file upload list, listing file upload list, and listing detail attached file list-->
<template>
    <form
    v-if="useForm"
    :action="previewUrl" method="POST" target="_blank">
        <input type="hidden" name="file_url" :value="url">

        <button type="submit" class="pointer fc-color7 a-nd inline-flex items-center btn-link">
            <i
            v-if="iconClass"
            class="fa listing-file-icon fa-lg"
            :class="[iconClass]"
            aria-hidden="true"></i>

            <div>{{ label }}</div>
        </button>
    </form>
    <a
    v-else
    class="pointer fc-color7 a-nd"
    :href="url"
    target="_blank"
    @click.prevent="openFile">
        <i
        v-if="iconClass"
        class="fa listing-file-icon fa-lg"
        :class="[iconClass]"
        aria-hidden="true"></i>

        <div>{{ label }}</div>
    </a>
</template>

<script>
let _includes = require('lodash.includes');

module.exports = {
    props: {
        label: {
            type: String,
            default: '',
        },

        url: {
            type: String,
            required: true,
        },

        mimeType: {
            type: String,
            default: '',
        },
    },

    data() {
        return {
            previewWindow: undefined,
        };
    },

    computed: {
        iconClass() {
            return this.getIconClass(this.mimeType);
        },

        previewUrl() {
            return '/dashboard/file/preview/';
        },

        useForm() {
            return _includes(
                [
                    'image/jpeg',
                    'image/x-ms-bmp',
                    'image/bmp',
                    'image/png',
                    'application/pdf',
                ],
                this.mimeType
            );
        },
    },

    methods: {
        openFileUrl(url) {
            if (this.windowIsOpen()) {
                this.previewWindow.focus();
                return;
            }

            var image = new Image();
            image.src = this.url;

            this.previewWindow = window.open('');
            this.previewWindow.document.write(image.outerHTML);
        },

        windowIsOpen() {
            let windowDefined = typeof this.previewWindow !== 'undefined';
            let windowClosed = windowDefined
                ? this.previewWindow.closed
                : false;

            return windowDefined && !windowClosed;
        },

        getIconClass(mimeType) {
            let icons = {
                'application/msword': 'fa-file-word-o',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                    'fa-file-word-o',
                'application/pdf': 'fa-file-pdf-o',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                    'fa-file-excel-o',
                'image/jpeg': 'fa-file-image-o',
                'image/png': 'fa-file-image-o',
                'image/x-ms-bmp': 'fa-file-image-o',
                'image/bmp': 'fa-file-image-o',
            };

            let hasIconType = typeof icons[mimeType] !== 'undefined';
            return hasIconType ? icons[mimeType] : 'fa-file-o';
        },

        openFile() {
            window.location = this.url;
        },
    },
};
</script>
