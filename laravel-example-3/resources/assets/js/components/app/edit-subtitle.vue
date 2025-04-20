<template>
    <div
    class="edit-subtitle lh-solid inline-flex items-center"
    :class="{
        'o-70': updating,
    }">
        <slot
        v-if="!editing"
        :edit-title="editTitle"></slot>

        <input-textual
        v-else
        label="Name"
        :name="inputName"
        :value="editTitle"
        wrap-class="hide-labels inline-block width-auto mr1 mb0"
        :input-maxlength="45"
        validation="min:1"
        :validation-message="validationMessage"
        @change="({value}) => editTitle = value"></input-textual>

        <i
        class="fa inline-block pointer ml1 edit-subtitle-icon"
        :class="{
            'fa-pencil not-editing': !editing,
            'fa-floppy-o editing': editing,
            'fz-30': !isInline || editing,
            'disabled': disabled
        }"
        aria-hidden="true"
        @click.stop="toggleEditing"></i>
    </div>
</template>

<script>
module.exports = {
    props: {
        isInline: {
            type: Boolean,
            default: false,
        },

        route: {
            type: String,
            default: '',
        },

        subtitle: {
            type: String,
            default: '',
        },
    },

    data() {
        return {
            editing: false,
            editTitle: this.subtitle,
            updating: false,
            currentTitle: this.subtitle,
            inputName: 'subtitle',
            disabled: false,
            validationMessage: '',
        };
    },

    computed: {},

    methods: {
        toggleEditing() {
            this.validationMessage = '';

            if (this.updating || this.disabled) {
                return;
            }

            this.editing = !this.editing;

            if (this.editing) {
                // Store the current title so we only update when its new.
                this.currentTitle = this.editTitle;
            } else {
                this.update();
            }
        },

        update() {
            if (this.updating || this.disabled) {
                return;
            }

            // No point in saving titles that have not changed.
            if (this.currentTitle === this.editTitle) {
                return;
            }

            this.updating = true;

            window.axios
                .patch(this.route, { subtitle: this.editTitle })
                .then(({ data }) => {
                    window.flashAlert(data.status, {
                        type: 'success',
                        timeout: 5000,
                    });
                    this.updating = false;
                })
                .catch(error => {
                    if (
                        error.response.status === 422 &&
                        error.response.data.errors[this.inputName][0] !==
                            undefined
                    ) {
                        this.validationMessage =
                            error.response.data.errors[this.inputName][0];
                    }

                    this.updating = false;
                    this.editing = true;
                });
        },
    },

    mounted() {
        window.Bus.$on(`${this.inputName}:invalid-input`, () => {
            this.disabled = true;
        });
        window.Bus.$on(`${this.inputName}:valid-input`, () => {
            this.disabled = false;
        });
    },
};
</script>
