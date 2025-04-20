<template>
    <div>
        <!-- Historical Financial Data  Period selection  -->
        <app-historical-financial-period-selection
        :year-value="mostRecentYear"
        :year-options="yearOptions"
        :quarter-value="mostRecentQuarter"></app-historical-financial-period-selection>

        <!--  Sources of Income  -->
        <app-form-accordion id="sources_of_income" header-title="Sources of Income">
            <template slot="header-right">
                <app-listing-section-completion-bar
                :listing="listing"
                section="sources_of_income"
                :form-id="formId"
                type="financial"></app-listing-section-completion-bar>
            </template>

            <template slot="content">
                <app-historical-financial-input-totals-repeater
                :data-most-recent-year="mostRecentYear"
                :data-most-recent-quarter="mostRecentQuarter"
                name="revenue"
                label="Revenue"
                name-placeholder="Name of Revenue Source"
                tooltip="Use the rows below to input your sources of revenue. Add additional rows if necessary"
                add-new-label="Add Additional Source of Income"
                :rows="sections.sources_of_income"
                :year-established="yearEstablished"></app-historical-financial-input-totals-repeater>
            </template>
        </app-form-accordion>

        <!--  Employee Related  Expenses  -->
        <app-form-accordion id="employee_related_expenses" header-title="Employee Related Expenses">
            <template slot="header-right">
                <app-listing-section-completion-bar
                :listing="listing"
                section="employee_related_expenses"
                :form-id="formId"
                type="financial"></app-listing-section-completion-bar>
            </template>

            <template slot="content">
                <app-historical-financial-input-totals
                :data-most-recent-year="mostRecentYear"
                :data-most-recent-quarter="mostRecentQuarter"
                :rows="sections.employee_related_expenses"
                :year-established="yearEstablished"></app-historical-financial-input-totals>
            </template>
        </app-form-accordion>

        <!--  Office Related Expenses  -->
        <app-form-accordion id="office_related_expenses" header-title="Office Related Expenses">
            <template slot="header-right">
                <app-listing-section-completion-bar
                :listing="listing"
                section="office_related_expenses"
                :form-id="formId"
                type="financial"></app-listing-section-completion-bar>
            </template>

            <template slot="content">
                <app-historical-financial-input-totals
                :data-most-recent-year="mostRecentYear"
                :data-most-recent-quarter="mostRecentQuarter"
                :rows="sections.office_related_expenses"
                :year-established="yearEstablished"></app-historical-financial-input-totals>
            </template>
        </app-form-accordion>

        <!--  Selling, General, and  Administrative Expenses  -->
        <app-form-accordion id="selling_general_and_administrative_expenses" header-title="Selling, General and Administrative Expenses">
            <template slot="header-right">
                <app-listing-section-completion-bar
                :listing="listing"
                section="selling_general_and_administrative_expenses"
                :form-id="formId"
                type="financial"></app-listing-section-completion-bar>
            </template>

            <template slot="content">
                <app-historical-financial-input-totals
                :data-most-recent-year="mostRecentYear"
                :data-most-recent-quarter="mostRecentQuarter"
                :rows="sections.selling_general_and_administrative_expenses"
                :year-established="yearEstablished"></app-historical-financial-input-totals>
            </template>
        </app-form-accordion>

        <!--  Finance Related Expenses  -->
        <app-form-accordion id="finance_related_expenses" header-title="Finance Related Expenses">
            <template slot="header-right">
                <app-listing-section-completion-bar
                :listing="listing"
                section="finance_related_expenses"
                :form-id="formId"
                type="financial"></app-listing-section-completion-bar>
            </template>

            <template slot="content">
                <app-historical-financial-input-totals
                :data-most-recent-year="mostRecentYear"
                :data-most-recent-quarter="mostRecentQuarter"
                :rows="sections.finance_related_expenses"
                :year-established="yearEstablished"></app-historical-financial-input-totals>
            </template>
        </app-form-accordion>

        <!--  Other Cash Flow Items  -->
        <app-form-accordion id="other_cash_flow_items" header-title="Other Cash Flow Items">
            <template slot="header-right">
                <app-listing-section-completion-bar
                :listing="listing"
                section="other_cash_flow_items"
                :form-id="formId"
                type="financial"></app-listing-section-completion-bar>
            </template>

            <template slot="content">
                <app-historical-financial-input-totals
                :data-most-recent-year="mostRecentYear"
                :data-most-recent-quarter="mostRecentQuarter"
                :rows="sections.other_cash_flow_items"
                :year-established="yearEstablished"></app-historical-financial-input-totals>
            </template>
        </app-form-accordion>

        <!--  Non Recurring, Personal or Extra Expenses  -->
        <app-form-accordion id="non_recurring_personal_or_extra_expenses" header-title="Non Recurring, Personal or Extra Expenses">
            <template slot="header-right">
                <app-listing-section-completion-bar
                :listing="listing"
                section="non_recurring_personal_or_extra_expenses"
                :form-id="formId"
                type="financial"></app-listing-section-completion-bar>
            </template>

            <template slot="content">
                <app-historical-financial-input-totals-repeater
                :data-most-recent-year="mostRecentYear"
                :data-most-recent-quarter="mostRecentQuarter"
                name="expense"
                label="Expense"
                name-placeholder="Name of Expense"
                tooltip="Include any one-time expense items or other expenses that wouldn't transfer to a new owner.
                Some examples include: salary paid to owner, company cars or other owner/employee perks, or extraordinary
                expenses related to natural disasters, theft, lawsuits, etc."
                add-new-label="Add Another Expense Adjustment"
                :rows="sections.non_recurring_personal_or_extra_expenses"
                :year-established="yearEstablished"></app-historical-financial-input-totals-repeater>
            </template>
        </app-form-accordion>

        <!--  Balance Sheet - Recurring Assets  -->
        <app-form-accordion id="balance_sheet_recurring_assets" header-title="Balance Sheet - Current Assets">
            <template slot="header-right">
                <app-listing-section-completion-bar
                :listing="listing"
                section="balance_sheet_recurring_assets"
                :form-id="formId"
                type="financial"></app-listing-section-completion-bar>
            </template>

            <template slot="content">
                <app-historical-financial-input-totals
                :data-most-recent-year="mostRecentYear"
                :data-most-recent-quarter="mostRecentQuarter"
                :rows="sections.balance_sheet_recurring_assets"
                :year-established="yearEstablished"></app-historical-financial-input-totals>
            </template>
        </app-form-accordion>

        <!--  Balance Sheet - Long Term Assets  -->
        <app-form-accordion id="balance_sheet_long_term_assets" header-title="Balance Sheet - Long Term Assets">
            <template slot="header-right">
                <app-listing-section-completion-bar
                :listing="listing"
                section="balance_sheet_long_term_assets"
                :form-id="formId"
                type="financial"></app-listing-section-completion-bar>
            </template>

            <template slot="content">
                <app-historical-financial-input-totals
                :data-most-recent-year="mostRecentYear"
                :data-most-recent-quarter="mostRecentQuarter"
                :rows="sections.balance_sheet_long_term_assets"
                :year-established="yearEstablished"></app-historical-financial-input-totals>
            </template>
        </app-form-accordion>

        <!--  Balance Sheet - Current Liabilities  -->
        <app-form-accordion id="balance_sheet_current_liabilities" header-title="Balance Sheet - Current Liabilities">
            <template slot="header-right">
                <app-listing-section-completion-bar
                :listing="listing"
                section="balance_sheet_current_liabilities"
                :form-id="formId"
                type="financial"></app-listing-section-completion-bar>
            </template>

            <template slot="content">
                <app-historical-financial-input-totals
                :data-most-recent-year="mostRecentYear"
                :data-most-recent-quarter="mostRecentQuarter"
                :rows="sections.balance_sheet_current_liabilities"
                :year-established="yearEstablished"></app-historical-financial-input-totals>
            </template>
        </app-form-accordion>

        <!--  Balance Sheet - Long-Term Liabilities  -->
        <app-form-accordion id="balance_sheet_long_term_liabilities" header-title="Balance Sheet - Long Term Liabilities">
            <template slot="header-right">
                <app-listing-section-completion-bar
                :listing="listing"
                section="balance_sheet_long_term_liabilities"
                :form-id="formId"
                type="financial"></app-listing-section-completion-bar>
            </template>

            <template slot="content">
                <app-historical-financial-input-totals
                :data-most-recent-year="mostRecentYear"
                :data-most-recent-quarter="mostRecentQuarter"
                :rows="sections.balance_sheet_long_term_liabilities"
                :year-established="yearEstablished"></app-historical-financial-input-totals>
            </template>
        </app-form-accordion>

        <!--  Balance Sheet - Shareholder's Equity  -->
        <app-form-accordion id="balance_sheet_shareholders_equity" header-title="Balance Sheet - Shareholder's Equity">
            <template slot="header-right">
                <app-listing-section-completion-bar
                :listing="listing"
                section="balance_sheet_shareholders_equity"
                :form-id="formId"
                type="financial"></app-listing-section-completion-bar>
            </template>

            <template slot="content">
                <app-historical-financial-input-totals
                :data-most-recent-year="mostRecentYear"
                :data-most-recent-quarter="mostRecentQuarter"
                :rows="sections.balance_sheet_shareholders_equity"
                :year-established="yearEstablished"></app-historical-financial-input-totals>
            </template>
        </app-form-accordion>
    </div>
</template>

<script>
export default {
    props: {
        dataYearOptions: {
            type: [Object, Array],
            default() {
                return [];
            }
        },

        dataMostRecentYear: {
            type: [Number, String],
            default() {
                return parseInt(
                    moment()
                        .subtract(1, "year")
                        .format("Y")
                );
            }
        },

        dataMostRecentQuarter: {
            type: [Number, String],
            default: 0
        },

        dataListing: {
            type: Object,
            required: true
        },

        dataFormId: {
            type: String,
            required: true
        },

        dataSections: {
            type: Object,
            required: true
        },

        dataYearEstablished: {
            type: [String, Number],
            default: 0
        }
    },

    data() {
        return {
            mostRecentYear: this.dataMostRecentYear,
            mostRecentQuarter: this.dataMostRecentQuarter,
            yearOptions: this.dataYearOptions,
            listing: this.dataListing,
            formId: this.dataFormId,
            sections: this.dataSections,
            yearEstablished: this.dataYearEstablished
        };
    },

    computed: {},

    methods: {},

    mounted() {
        window.Bus.$on("hf-period-selection:year-changed", year => {
            this.mostRecentYear = year
                ? year
                : parseInt(
                      moment()
                          .subtract(1, "year")
                          .format("Y")
                  );
        });

        window.Bus.$on("hf-period-selection:quarter-changed", quarter => {
            this.mostRecentQuarter = quarter;
        });
    }
};
</script>
