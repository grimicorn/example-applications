<template>
    <div
        class="input-file-wrap input-wrap"
        :class="[
            this.error ? 'has-error' : '',
            'is-file'
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
            <button
                type="button"
                class="input-file-button button-small"
                :disabled="disabled || readonly"
            >
                {{ buttonLabel }}
                <input
                    ref="file_input"
                    type="file"
                    :name="name"
                    :id="id"
                    class="input-file absolute pin opacity-0"
                    :class="inputClass"
                    :disabled="disabled"
                    :readonly="readonly"
                    :dusk="dusk"
                    :autofocus="autofocus"
                    :multiple="multiple"
                    :accept="accept.join(',')"
                    @input="handleInput"
                    @blur="clearErrors"
                >
            </button>
            <span
                v-text="uploadedLabel"
                v-if="displayUploadLabel"
                class="input-file-label"
            ></span>
        </slot>


        <input-error
            :data-error="error"
        ></input-error>

        <input-instructions
            :data-instructions="acceptLabel"
        ></input-instructions>

        <input-instructions
            :data-instructions="instructions"
        ></input-instructions>

        <slot name="after"></slot>
    </div>
</template>

<script>
import inputMixin from "mixins/input";

export default {
    mixins: [inputMixin],

    props: {
        dataMultiple: {
            default: false,
            type: Boolean
        },

        dataAccept: {
            type: Array,
            default() {
                return [];
            }
        },

        dataDisplayUploadLabel: {
            type: Boolean,
            default: true
        },

        dataButtonSingularLabel: {
            type: String,
            default: "Select file"
        },

        dataButtonPluralLabel: {
            type: String,
            default: "Select files"
        }
    },

    data() {
        return {
            value: "",
            multiple: this.dataMultiple,
            accept: this.dataAccept,
            files: null,
            displayUploadLabel: this.dataDisplayUploadLabel
        };
    },

    computed: {
        buttonLabel() {
            if (this.multiple) {
                return this.dataButtonPluralLabel;
            }

            return this.dataButtonSingularLabel;
        },

        acceptLabel() {
            if (!this.accept) {
                return "";
            }

            if (Object.keys(this.accept).length === 0) {
                return "";
            }

            return `Only ${this.accept.join(",")} files allowed.`;
        },

        hasFiles() {
            let isFileList = this.files instanceof FileList;
            if (!this.files || !isFileList) {
                return false;
            }

            return this.files.length >= 1;
        },

        uploadedLabel() {
            if (!this.hasFiles) {
                return "No file chosen";
            }

            if (this.files.length === 1 && !this.multiple) {
                return this.files[0].name;
            }

            if (this.files.length === 1) {
                return `${this.files.length} file`;
            }

            return `${this.files.length} files`;
        }
    },

    methods: {
        reset() {
            this.value = "";
        },

        handleInput($event) {
            this.files = this.$refs["file_input"].files;

            this.emitInput($event, this.files);
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
        this.setFormId().then(formId => {
            window.Bus.$on(`${formId}.submitted`, () => {
                this.reset();
            });
        });
    }
};
</script>
