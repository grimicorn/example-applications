<template>
    <app-form-accordion
    header-title="More About The Business"
    class="listing-details-more-about-the-business">

        <div slot="content">
            <!-- Year Established -->
            <input-textual
            name="year_established"
            :value="listing.year_established"
            label="Year Established"
            placeholder="Year Established"
            wrap-class="input-half is-left"
            validation="digits:4"></input-textual>

            <!-- Number of Employees -->
            <input-textual
            name="number_of_employees"
            :value="listing.number_of_employees"
            placeholder="Number"
            label="Number of Employees"
            wrap-class="input-half is-right"></input-textual>

            <input-textual
            type="textarea"
            name="location_description"
            :value="listing.location_description"
            placeholder="Description of the business location. You can also include other information such as type of space, size, or lease terms, if applicable."
            label="Location Description"
            wrap-class="clear"
            :input-maxlength="1000"></input-textual>

            <!--Links-->
            <input-repeater
                class="professional-information-links"
                :values="listing.links"
                :default-value="['']"
                label="Link(s):"
                data-tooltip="We understand that not all businesses have a website and that providing one may not be appropriate for all situations. Thus, there is no penalty for not including it."
                add-new-label="Add new link"
            >
                <template scope="props">
                    <input-textual
                            type="url"
                            :name="`links[${props.index}]`"
                            :value="typeof props.input === 'string' ? props.input : ''"
                            placeholder="Website Address"
                            wrap-class="hide-labels"></input-textual>
                </template>
            </input-repeater>

        </div>
    </app-form-accordion>
</template>

<script>
module.exports = {
    props: {
        dataListing: {
            type: Object,
            required: true,
        },

        formId: {
            type: String,
            default: '',
        },

        percentageComplete: {
            type: Number,
            default: 0,
        },
    },

    data() {
        return {
            listing: this.dataListing,
        };
    },

    methods: {},
};
</script>
