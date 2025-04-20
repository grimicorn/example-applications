<template>
    <div class="profile-settings-update-password">
        <div class="profile-settings-username-wrap">
            <h3 class="profile-settings-username-label">
                User Name: <span class="profile-settings-username" v-text="username"></span>
                <tool-tip
                        direction="bottom">
                    The security of your account is important to us. If you need to make a change to the email address
                    associated with your account, please contact us by clicking <a href="/contact">here</a>. This extra step helps protect
                    your account from unauthorized changes.
                </tool-tip>
            </h3>
        </div>

        <button
        v-if="!displayPasswordFields"
        class="profile-settings-change-pasword-btn"
        type="button"
        @click="displayPasswordFields = true">
            Change Password
        </button>

        <fe-form
        form-id="update-password-form"
        class="update-password-form clearfix"
        method="PATCH"
        submit-label="Update"
        :action="route"
        v-if="displayPasswordFields">
            <input-textual
            name="current_password"
            type="password"
            label="Current Password"
            :validation-message="getFieldValidationError('current_password')"
            :value="getFieldValue('current_password')"
            ></input-textual>

            <input-textual
            name="password"
            type="password"
            label="New Password"
            :validation-message="getFieldValidationError('password')"
            :value="getFieldValue('password')"
            ></input-textual>

            <input-textual
            name="password_confirmation"
            type="password"
            label="Confirm Password"
            :validation-message="getFieldValidationError('password_confirmation')"
            :value="getFieldValue('password_confirmation')"
            ></input-textual>

            <input type="hidden" name="password_update" value="1">
        </fe-form>
    </div>
</template>

    <script>
        module.exports = {
            props: {
                username: {
                    type: String,
                    required: true,
                },

                route: {
                    type: String,
                    required: true,
                },

                submissionErrors: {
                    type: Object,
                    default() {
                        return {};
                    },
                },

                values: {
                    type: Object,
                    default() {
                        return {};
                    },
                },

                displayFields: {
                    type: Boolean,
                    default: false
                }
            },

            data() {
                return {
                    displayPasswordFields: this.displayFields,
                };
            },

            computed: {
                oldValue() {
                    return this.getFieldValue('current_password');
                },

                newValue() {
                    return this.getFieldValue('password');
                },

                confirmValue() {
                    return this.getFieldValue('password_confirmation');
                },
            },

            methods: {
                getFieldValidationError(field) {
                    let errors = this.submissionErrors;

                    if (typeof errors[field] !== 'undefined') {
                        return errors[field];
                    }

                    return '';
                },

                getFieldValue(field) {
                    let values = this.values;

                    if (typeof values[field] !== 'undefined') {
                        return values[field];
                    }

                    return '';
                },
            },
        };
    </script>
