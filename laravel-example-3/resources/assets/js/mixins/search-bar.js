module.exports = {
    props: {
        defaultZipRadius: {
            type: Number,
            default: 100,
        },
    },

    data() {
        return {
            search: this.defaultSearchValues(),
        };
    },

    computed: {
        statesForSelect() {
            return typeof window.Spark.statesForSelect === 'undefined'
                ? []
                : window.Spark.statesForSelect;
        },

        listingUpdatedOptions() {
            return [
                {label: 'Within the last month', value: 'last_month'},

                {
                    label: 'Within the last three months',
                    value: 'last_three_months',
                },

                {label: 'Within the last year', value: 'last_year'},
            ];
        },

        transactionOptions() {
            return [
                {label: 'Any Transaction Type', value: ''},

                {label: 'Brokered', value: 'brokered'},

                {label: 'For Sale by Owner', value: 'for_sale_by_owner'},
            ];
        },
    },

    methods: {
        resetSearch() {
            this.search = this.defaultSearchValues();
            window.Bus.$emit('listing-search.reset');
        },

        submitSearch() {
            window.Bus.$emit('submit-search');
        },

        defaultSearchValues() {
            return {
                name: '',
                business_categories: [],
                location: '',
                transaction_type: '',
                listing_updated: '',
                keyword: '',
                zip_code_radius: this.defaultZipRadius,
                zip_code: '',
                city: '',
                state: '',
                asking_price_min: '',
                asking_price_max: '',
                cash_flow_min: '',
                cash_flow_max: '',
                pre_tax_income_min: '',
                pre_tax_income_max: '',
                ebitda_min: '',
                ebitda_max: '',
                revenue_min: '',
                revenue_max: '',
            };
        },
    },
};
