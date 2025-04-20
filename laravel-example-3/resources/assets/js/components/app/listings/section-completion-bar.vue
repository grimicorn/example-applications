<template>
    <div class="listing-section-completion-bar-wrap relative" :class="{
        'o-70': loading || retrievingScore,
    }">
        <span class="listing-section-completion-label fc-color7" v-if="displayLabel">Section Completion:</span>

        <div class="listing-section-completion-bar" :class="{
            'bg-color2': !barAltBackground,
            'bg-color3': barAltBackground,
        }">
            <div class="listing-section-completion-bar-inner bg-color5" :style="`width: ${percentage}%`"></div>
        </div>

        <span v-if="!loading && !retrievingScore" class="listing-section-completion-percentage fc-color4" v-text="`${percentage}%`"></span>

        <loader :loading="loading || retrievingScore" :cover="false" :spinner-large="false"></loader>
    </div>
</template>

<script>
let _foreach = require('lodash.foreach');
let _includes = require('lodash.includes');
let debounce = require('./../../../debounce.js');

module.exports = {
    props: {
        percentageComplete: {
            type: Number,
            default: 0, // 0 -1
        },

        displayLabel: {
            type: Boolean,
            default: true,
        },

        barAltBackground: {
            type: Boolean,
            default: false,
        },

        formId: {
            type: String,
            default: '',
        },

        section: {
            type: String,
            default: '',
        },

        type: {
            type: String,
            default: '',
        },

        listing: {
            type: Object,
        },
    },

    data() {
        return {
            retrievingScore: false,
            percentage: this.formatPercentage(this.percentageComplete),
            loading: true,
            resendOnceFinshed: false,
            resendFormData: undefined,
            resendInput: undefined,
            latestFormData: undefined,
            latestInput: undefined,
            debounce: debounce.new(),
        };
    },

    computed: {
        url() {
            let listingId = 0;

            // Make sure we have a listing id.
            if (typeof this.listing !== 'undefined') {
                listingId = this.listing.id;
            }

            // Make sure we have a section and type.
            if (this.type === '' || this.section === '') {
                return '';
            }

            return `/dashboard/listing/${listingId}/completion-score/${
                this.type
            }/${this.section}`;
        },

        overviewSectionFields() {
            return {
                basics: [
                    'title',
                    'business_category_id',
                    'business_sub_category_id',
                    'business_name',
                    'asking_price',
                    'summary_business_description',
                    'business_description',
                    'city',
                    'state',
                    'zip_code',
                ],
                more_about_the_business: [
                    'year_established',
                    'number_of_employees',
                    'location_description',
                    'address_1',
                    'city',
                    'state',
                    'zip_code',
                    'address_visible',
                ],
                financial_details: [
                    'revenue',
                    'ebitda',
                    'pre_tax_earnings',
                    'discretionary_cash_flow',
                    'inventory_included',
                    'inventory_estimated',
                    'inventory_description',
                    'fixtures_equipment_included',
                    'fixtures_equipment_estimated',
                    'fixtures_equipment_description',
                    'real_estate_included',
                    'real_estate_estimated',
                    'real_estate_description',
                ],
                business_details: [
                    'products_services',
                    'market_overview',
                    'competitive_position',
                    'business_performance_outlook',
                ],
                transaction_considerations: [
                    'reason_for_selling',
                    'desired_sale_date',
                    'financing_available',
                    'financing_available_description',
                    'support_training',
                    'support_training_description',
                    'seller_non_compete',
                ],
                uploads: ['photos'],
            };
        },

        financialSectionFields() {
            return {
                historical_financial_data_period_selection: [
                    'hf_most_recent_year',
                    'hf_most_recent_quarter',
                ],
                sources_of_income: ['[revenue]'],
                employee_related_expenses: [
                    'employee_wages_benefits',
                    'employee_education_training',
                    'contractor_expenses',
                ],
                office_related_expenses: [
                    'utilities',
                    'rent_lease_expenses',
                    'office_supplies',
                ],
                selling_general_and_administrative_expenses: [
                    'cost_goods_sold',
                    'transportation',
                    'meals_entertainment',
                    'travel_expenses',
                    'professional_services',
                ],
                finance_related_expenses: [
                    'interest_expense',
                    'depreciation',
                    'amortization',
                    'general_operational_expenses',
                    'business_taxes',
                ],
                other_cash_flow_items: [
                    'capital_expenditures',
                    'stock_based_compensation',
                    'change_working_capital',
                ],
                non_recurring_personal_or_extra_expenses: ['[expense]'],
                balance_sheet_recurring_assets: [
                    'cash_equivalents',
                    'investments',
                    'accounts_receivable',
                    'inventory',
                    'prepaid_expenses',
                    'other_current_assets',
                ],
                balance_sheet_long_term_assets: [
                    'property_plant_equipment',
                    'goodwill',
                    'intangible_assets',
                    'other_assets',
                ],
                balance_sheet_current_liabilities: [
                    'accounts_payable',
                    'accrued_liabilities',
                    'unearned_revenues',
                    'other_current_liabilities',
                ],
                balance_sheet_long_term_liabilities: [
                    'long_term_debt',
                    'deferred_income_taxes',
                    'deferred_rent_expense',
                    'other_liabilities',
                ],
                balance_sheet_shareholders_equity: [
                    'paid_in_capital',
                    'retained_earnings',
                    'other_equity_accounts',
                ],
            };
        },

        sectionFields() {
            if (this.type === 'overview') {
                return this.overviewSectionFields;
            }

            if (this.type === 'financial') {
                return this.financialSectionFields;
            }

            return {};
        },

        isUploads() {
            return 'uploads' === this.section;
        },
    },

    methods: {
        formatPercentage(percentage) {
            return Math.round(percentage * 100);
        },

        shouldGetScore() {
            // Only need to get the intial score if one is not provided.
            if (this.percentageComplete > 0) {
                return false;
            }

            return !this.retrievingScore && this.url;
        },

        isPeriodOverviewUpdate(name) {
            if (this.type !== 'financial') {
                return false;
            }

            return _includes(
                this.sectionFields.historical_financial_data_period_selection,
                name
            );
        },

        checkFieldSectionByName(fields, name) {
            if (this.isPeriodOverviewUpdate(name)) {
                return true;
            }

            if (_includes(fields, name)) {
                return true;
            }

            let found = false;
            _foreach(fields, field => {
                if (name.indexOf(field) >= 0) {
                    found = true;
                }
            });

            return found;
        },

        getInputFieldSections(input) {
            let matched = [];

            _foreach(this.sectionFields, (fields, property) => {
                if (this.checkFieldSectionByName(fields, input.name)) {
                    matched.push(property);
                }
            });

            return matched;
        },

        shouldUpdateScore(input) {
            if (!this.url || this.loading) {
                return false;
            }

            if (this.isUploads) {
                return input.name.indexOf('photos') >= 0;
            }

            return _includes(this.getInputFieldSections(input), this.section);
        },

        updatedPercentage(value) {
            value = parseFloat(value, 10);
            if (!isNaN(value)) {
                this.percentage = this.formatPercentage(value);
            }

            this.retrievingScore = false;
            this.loading = false;
        },

        getScore(force = false) {
            if (!this.shouldGetScore() && !force) {
                window.Vue.nextTick(() => {
                    this.loading = false;
                });
                return;
            }

            this.retrievingScore = true;
            window.axios
                .get(this.url)
                .then(({ data }) => {
                    this.updatedPercentage(data.value);
                })
                .catch(function(error) {
                    this.retrievingScore = false;
                    this.loading = false;
                });
        },

        getUpdatedScore(formData, input, force = false) {
            // Check if the score should be updated.
            if (!this.shouldUpdateScore(input) && !force) {
                return;
            }

            // This will minimize the requests without missing events.
            if (this.retrievingScore) {
                this.resendOnceFinshed = true;
                this.resendFormData = formData;
                this.resendInput = input;

                return;
            }

            // Stop multi-updates
            this.retrievingScore = true;

            window.axios
                .post(this.url, formData)
                .then(({ data }) => {
                    this.updatedPercentage(data.value);

                    if (this.resendOnceFinshed) {
                        this.getUpdatedScore(
                            this.resendFormData,
                            this.resendInput,
                            true
                        );
                    }

                    this.resendOnceFinshed = false;
                })
                .catch(function(error) {
                    this.retrievingScore = false;
                });
        },

        updateScore() {
            this.debounceScoreUpdate(() => {
                this.getUpdatedScore(
                    new FormData(document.getElementById(this.formId)),
                    this.latestInput,
                    true
                );
            });
        },

        debounceScoreUpdate(callback) {
            this.debounce.set(() => {
                window.Vue.nextTick(callback);
            });
        },
    },

    created() {
        // If we were not able to build up the URL then we should be ready.
        if (this.url === '' || this.isUploads) {
            this.loading = false;
        }

        this.getScore();
    },

    mounted() {
        // Handle general inputs
        window.Bus.$on(
            `${this.formId}.updated`,
            (formData, input, submitting) => {
                if (submitting) {
                    return;
                }

                this.latestFormData = formData;
                this.latestInput = input;

                this.debounceScoreUpdate(() => {
                    let force = false;

                    if (this.isUploads) {
                        force =
                            'file_upload_submission_inputs_update' ===
                            input.name;
                    }
                    this.getUpdatedScore(formData, input, force);
                });
            }
        );

        window.Bus.$on('listing-photo:deleted', () => {
            if (this.type === 'overview' && this.isUploads) {
                this.updateScore();
            }
        });

        // Handle revenue and expense added/remove
        window.Bus.$on('input-totals-repeater:updated', name => {
            // Check revenue.
            if (name === 'revenue' && this.section === 'sources_of_income') {
                this.updateScore();
            }

            // Check expense.
            if (
                name === 'expense' &&
                this.section === 'non_recurring_personal_or_extra_expenses'
            ) {
                this.updateScore();
            }
        });
    },
};
</script>
