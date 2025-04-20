<template>
    <div
        class="input-images-wrap input-wrap"
        :class="[
            this.error ? 'has-error' : '',
        ]"
    >
        <slot name="before"></slot>

        <slot name="label">
            <label
                v-if="label"
                :for="id"
                class="input-label"
                v-text="label"
            ></label>
        </slot>

        <slot name="input">
            <div
                :class="inputClass"
            >
                <input-file
                    :data-name="name"
                    :data-id="id"
                    v-model="value"
                    :data-placeholder="placeholder"
                    :data-disabled="disabled"
                    :data-readonly="readonly"
                    :data-dusk="dusk"
                    :data-autofocus="autofocus"
                    :data-display-upload-label="false"
                    :data-multiple="multiple"
                    :data-accept="accept"
                    data-button-singular-label="Select image"
                    data-button-plural-label="Select images"
                    @input="handleInput"
                    @blur="clearErrors"
                ></input-file>
            </div>
        </slot>

        <slot name="images" :images="images">
            <ul class="input-image-previews list-reset flex flex-wrap -mx-4 -mb-4">
                <li
                    v-for="image in images"
                    :key="image.id"
                    class="w-32 h-32 mx-4 mb-4 flex items-center justify-center bg-brand-lighter rounded"
                >
                    <img
                        :src="image.src"
                        :alt="image.name"
                    >
                </li>
            </ul>
        </slot>

        <input-error
            :data-error="error"
        ></input-error>

        <input-instructions
            :data-instructions="instructions"
        ></input-instructions>

        <slot name="after"></slot>
    </div>
</template>

<script>
import inputMixin from "mixins/input";
import _foreach from "lodash.foreach";

export default {
    mixins: [inputMixin],

    props: {
        dataValue: {
            type: [Array, Object, FileList],
            default() {
                return [];
            }
        },

        dataMultiple: {
            default: true,
            type: Boolean
        },

        dataImages: {
            default() {
                return [];
            },
            type: [Array, Object]
        }
    },

    data() {
        return {
            value: this.dataValue,
            uploadedFiles: [],
            multiple: this.dataMultiple
        };
    },

    computed: {
        accept() {
            return ["jpeg", "png", "gif", "svg"];
        },

        images() {
            if (!this.multiple && this.uploadedFiles.length > 0) {
                return this.uploadedFiles;
            }

            let images = Object.values(this.dataImages);

            return this.uploadedFiles.concat(images);
        }
    },

    methods: {
        setUploadedFiles() {
            let files = this.value;
            this.uploadedFiles = [];

            _foreach(files, (file, index) => {
                this.getBase64(file)
                    .then(result => {
                        this.uploadedFiles.push({
                            alt: file.name,
                            src: result,
                            id: `new_${index}`
                        });
                    })
                    .catch(error => {});
            });
        },

        handleInput($event) {
            window.Vue.nextTick(() => {
                this.emitInput($event, this.value);
            });

            this.setUploadedFiles();
        },

        getBase64(file) {
            return new Promise((resolve, reject) => {
                let reader = new FileReader();
                reader.readAsDataURL(file);

                reader.onload = function() {
                    resolve(reader.result);
                };
                reader.onerror = function(error) {
                    resolve(error);
                };
            });
        }
    },

    watch: {
        formErrors(errors) {
            this.setErrors(errors);
        },

        dataValue() {
            this.value = this.dataValue;
        }
    },

    mounted() {
        this.setFormId();
    }
};
</script>
