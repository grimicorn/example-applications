<template>
    <fe-form
    form-id="enable_two_factor_auth_form"
    :remove-submit="true"
    :disabled-unload="true"
    class="enable-two-factor-auth-form clearfix" role="form">
        <!-- Country Code -->
        <input-textual
        validation="numeric"
        name="country_code"
        placeholder="1"
        wrap-class="width-15 inline-block pr2 block pull-left"
        label="Country Code"
        :value="countryCode"
        :validation-message="countryCodeError"
        :data-ignore-dirty-check="true"
        @change="handleCountryCodeChange"></input-textual>

        <!--  Phone Number -->
        <input-textual
        type="phone"
        name="phone"
        wrap-class="inline-block width-35 pr2 block pull-left"
        label="Phone Number"
        :value="phone"
        :validation-message="phoneError"
        :data-ignore-dirty-check="true"
        @change="handlePhoneChange"></input-textual>

        <!--  Verify Phone Number -->
        <input-textual
        type="phone"
        name="phone_verify"
        wrap-class="inline-block width-35 pr2 block pull-left"
        label="Verify Phone Number"
        :value="phoneVerification"
        :validation-message="phoneVerificationError"
        @change="handlePhoneVerificationChange"></input-textual>

        <!--  Enable Button -->
        <div class="input-alignment-label-spacer">
            <button
            type="submit"
            @click.prevent="handleSubmit"
            class="enable-two-factor-auth-form-submit width-15 block pull-left fe-input-height ma0"
            :disabled="buttonDisabled">
                <span v-if="busy">
                    <i class="fa fa-btn fa-spinner fa-spin"></i>Enabling
                </span>
                <span v-else>Enable</span>
            </button>
        </div>
    </fe-form>
</template>

<script>
let _foreach = require("lodash.foreach");

export default {
    props: {
        dataCountryCodeError: {
            type: String,
            default: ""
        },

        dataPhoneError: {
            type: String,
            default: ""
        },

        dataForm: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            phoneVerification: "",
            initialPhoneVerification: "",
            initialCountryCode: "",
            initialPhone: "",
            formInteracted: false
        };
    },

    computed: {
        phoneVerified: {
            get() {
                return this.getPhoneVerified();
            },

            set() {}
        },

        phoneVerificationError: {
            get() {
                if (this.phoneVerified) {
                    return "";
                }

                return "Phone numbers do not match";
            },

            set() {}
        },

        countryCodeError: {
            get() {
                return this.dataCountryCodeError;
            },

            set() {}
        },

        phoneError: {
            get() {
                return this.dataPhoneError;
            },

            set() {}
        },

        countryCode: {
            get() {
                return this.dataForm.country_code;
            },

            set() {}
        },

        phone: {
            get() {
                return this.dataForm.phone;
            },

            set() {}
        },

        busy: {
            get() {
                return this.dataForm.busy;
            },

            set() {}
        },

        buttonDisabled: {
            get() {
                if (this.phoneVerificationError) {
                    return true;
                }

                return this.busy;
            },

            set() {}
        }
    },

    methods: {
        getPhoneVerified() {
            if (!this.formInteracted) {
                return true;
            }

            return this.phoneVerification === this.phone;
        },

        handleSubmit() {
            this.formInteracted = true;
            if (!this.phoneVerified) {
                return;
            }

            this.$emit("submit");
        },

        handleChange(key, input) {
            this.phoneVerified = this.getPhoneVerified();
            this.$emit(`change:${key}`, input);
        },

        handleCountryCodeChange(input) {
            this.countryCode = input.value;
            this.handleChange("contry-code", input);
        },

        handlePhoneChange(input) {
            this.phone = input.value;
            this.handleChange("phone", input);
        },

        handlePhoneVerificationChange(input) {
            this.phoneVerification = input.value;
            this.handleChange("phone-verify", input);
        }
    },

    mounted() {
        _foreach(this.$el.querySelectorAll("input"), $el => {
            $el.addEventListener("focus", () => {
                this.formInteracted = true;
            });
        });
    }
};
</script>
