<template>
    <form class="row" role="form">
        <div class="col-sm-6">
            <!-- Name on Card -->
            <input-textual
            name="name"
            label="Name on Card"
            v-model="cardForm.name"
            :value="cardForm.name"
            @change="emitChange"></input-textual>

            <!-- Address -->
            <input-textual
            name="address"
            label="Address"
            v-model="form.address"
            :value="form.address"
            validation="required"
            :validation-message="allErrors['address']"
            @change="emitChange"></input-textual>

            <!-- Address Line 2 -->
            <input-textual
            name="address_line_2"
            label="Address Line 2"
            v-model="form.address_line_2"
            :value="form.address_line_2"
            :validation-message="allErrors['address_line_2']"
            @change="emitChange"></input-textual>

            <!-- City -->
            <input-textual
            name="city"
            label="City"
            v-model="form.city"
            :value="form.city"
            validation="required"
            :validation-message="allErrors['city']"
            @change="emitChange"></input-textual>
            <div class="row">
                <div class="col-sm-6">
                    <!-- State & ZIP Code -->
                    <input-select
                        name="state"
                        placeholder="State"
                        :allow-placeholder-select="true"
                        :options="statesForSelect"
                        label="State"
                        v-model="form.state"
                        :value="form.state"
                        validation="required"
                        :validation-message="allErrors['state']"
                        @change="emitChange"></input-select>
                </div>
                <div class="col-sm-6">
                    <!-- Zip Code -->
                    <input-textual
                        name="zip"
                        label="Zip Code"
                        v-model="form.zip"
                        :value="form.zip"
                        validation="required"
                        :validation-message="allErrors['zip']"
                        @change="emitChange"></input-textual>
                </div>
            </div>


        </div>

        <div class="col-sm-6">
            <!-- Card Number -->
            <input-textual
            name="number"
            label="Card Number"
            v-model="cardForm.number"
            :value="cardForm.number"
            data-stripe="number"
            :placeholder="dataCardPlaceholder"
            validation="required"
            :validation-message="allErrors['number']"
            @change="emitChange"></input-textual>

            <!-- Card Expiration Date: -->
            <input-credit-card-expiration
            :month="cardForm.month"
            :year="cardForm.year"
            @month-change="monthChange"
            @year-change="yearChange"
            @change="emitChange"></input-credit-card-expiration>

            <!-- Card CVV -->
            <div class="clearfix flex fe-input-mb">
                <input-textual
                name="cvc"
                label="Card CVV"
                v-model="cardForm.cvc"
                data-stripe="cvc"
                :value="cardForm.cvc"
                validation="required"
                wrap-class="mb0 pr2 width-25 lh-title nowrap"
                @change="emitChange"></input-textual>

                <div class="width-75">
                    <strong class="fz-14 inline-block mt3">
                        *3 digit security code on the back of your card, 4 digits on the front for AMEX.
                    </strong>
                </div>
            </div>

            <img
            src="/img/card-logos.png"
            width="343"
            height="81"
            class="mt2 mb2">
        </div>

        <div class="col-sm-12 text-right">
            <!-- Back Button -->
            <button
            @click="back"
            class="btn btn-color7"
            v-if="displayBack">Back</button>

            <!-- Update Button -->
            <button
            type="submit"
            @click.prevent="emitUpdate"
            :disabled="busy">
                <span>
                    <i
                    v-if="busy"
                    class="fa fa-btn fa-spinner fa-spin"></i>
                    {{ busy ? submitBusyLabel : submitLabel }}
                </span>
            </button>
        </div>
    </form>
</template>

<script>
let _merge = require('lodash.merge');

module.exports = {
    mixins: [require('./../../../../mixins/utilities.js')],

    props: {
        planId: {
            type: String,
            default: '',
        },

        updateForm: {
            type: Boolean,
            default: false,
        },

        displayBack: {
            type: Boolean,
            default: false,
        },

        busy: {
            type: Boolean,
            default: false,
        },

        cardFormErrors: {
            type: Object,
            default() {
                return {};
            },
        },

        formErrors: {
            type: Object,
            default() {
                return {};
            },
        },

        dataCardForm: {
            type: Object,
            default() {
                return {
                    name: '',
                    number: '',
                    cvc: '',
                    year: '',
                    month: '',
                };
            },
        },

        dataCardFormOverrides: {
            type: Object,
            default() {
                return {};
            },
        },

        dataForm: {
            type: Object,
            default() {
                return {
                    address: '',
                    address_line_2: '',
                    city: '',
                    state: '',
                    zip: '',
                    country: '',
                    vat_id: null,
                };
            },
        },

        dataFormOverrides: {
            type: Object,
            default() {
                return {};
            },
        },

        dataCardPlaceholder: {
            type: String,
            default: '',
        },
    },

    data() {
        return {
            cardForm: _merge(this.dataCardForm, this.dataCardFormOverrides),
            form: _merge(this.dataForm, this.dataFormOverrides),
            submitBusyLabel: this.updateForm ? 'Updating' : 'Submitting',
            submitLabel: this.updateForm ? 'Update' : 'Submit',
            allErrors: {},
            errorMessage:
                'There was a problem with your payment. See below for errors.',
        };
    },

    computed: {
        statesForSelect() {
            return window.Spark.statesForSelect;
        },
    },

    methods: {
        getErrors() {
            return {
                address: this.cardFormErrorsGet('address'),
                address_line_2: this.cardFormErrorsGet('address_line_2'),
                city: this.cardFormErrorsGet('city'),
                state: this.cardFormErrorsGet('state'),
                zip: this.cardFormErrorsGet('zip'),
                number: this.cardFormErrorsGet('number'),
                name: this.cardFormErrorsGet('name'),
            };
        },

        emitUpdate() {
            this.$emit('update');
            window.Bus.$emit('payment-form:update');
        },

        back() {
            this.$emit('back');
        },

        cardFormErrorsGet(name) {
            let allErrors = this.mergedErrors();
            let errors =
                typeof allErrors[name] === 'undefined' ? [] : allErrors[name];

            return typeof errors[0] === 'undefined' ? '' : errors[0];
        },

        mergedErrors() {
            return _merge(this.cardFormErrors, this.formErrors);
        },

        emitChange() {
            setTimeout(() => {
                // Make sure the country is set to US.
                this.form.country = 'US';

                // Let the world know.
                this.$emit('card-form-change', this.cardForm);
                this.$emit('form-change', this.form);
            }, 250);
        },

        monthChange(value) {
            this.cardForm.month = value;
            this.form.month = value;
            this.emitChange();
        },

        yearChange(value) {
            this.cardForm.year = value;
            this.emitChange();
        },

        clearErrorAlert() {
            window.clearAlert(this.errorMessage);
        },
    },

    watch: {
        dataCardForm() {
            this.cardForm = this.dataCardForm;
        },

        dataForm() {
            this.form = this.dataForm;
        },

        cardFormErrors() {
            this.allErrors = this.getErrors();
        },
    },

    mounted() {
        if (this.planId) {
            this.form.plan = this.planId;
            this.emitChange();
        }

        this.allErrors = this.getErrors();

        window.Bus.$on('payment-token-error', response => {
            window.flashAlert(this.errorMessage, {
                type: 'error',
            });
        });
    },
};
</script>
