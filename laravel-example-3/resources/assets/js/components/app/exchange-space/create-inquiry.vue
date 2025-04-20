<template>
    <div
    v-if="!listing.current_user_is_owner">
        <div
        v-if="displayStatus"
        class="inline-block pb1 pt1 text-bold">
            <a
            :href="currentUserActiveSpace.show_url"
            class="a-color7 a-hover-ul">Status: {{ currentUserActiveSpace.status_label }}</a>
        </div>

        <div
        v-else-if="contactingSeller"
        class="inline-block pb1 pt1 text-bold fc-success">
            <loader
            :cover="false"
            :spinner-large="false"
            :loading="true"
            :inline="true"
            color-class="fc-success"></loader> Contacting Seller
        </div>

        <button
        v-else-if="!loggedIn"
        @click="openRequiresAuthModal"
        v-text="buttonLabel"></button>

        <modal
        v-else
        :button-label="buttonLabel"
        title="Contact the seller of this business"
        :auto-open="isOpen"
        @opened="handleModelOpen"
        @closed="handleModalClose">
            <div v-cloak>
                <fe-form
                :form-id="formId"
                :action="listing.inquiry_create_url"
                method="POST"
                class="buyer-inquiry-create"
                submit-label="Submit"
                :submit-class="submitClass"
                :submit-centered="true"
                :should-ajax="true">
                    <input
                    type="hidden"
                    name="listing_id"
                    :value="listing.id">

                    <input
                    type="hidden"
                    name="buyer_inquiry_start"
                    :value="listing.id">

                    <input-textual
                    label="Message"
                    name="body"
                    type="textarea"
                    v-model="body"></input-textual>
                </fe-form>
            </div>
        </modal>
    </div>
</template>

<script>
module.exports = {
    name: "app-create-inquiry",

    props: {
        listing: {
            type: Object,
            required: true
        },

        submitClass: {
            type: String,
            default: "btn btn-color4"
        }
    },

    data() {
        return {
            buttonLabel: "Start an Exchange Space",
            contactingSeller: false,
            body: "",
            modalListingId: 0,
            currentUserActiveSpace: this.listing.current_user_active_space
        };
    },

    computed: {
        displayStatus() {
            return this.hasActiveSpace && this.loggedIn;
        },

        space: {
            get() {
                return this.currentUserActiveSpace;
            },

            set() {}
        },

        hasActiveSpace() {
            return !!this.space;
        },

        formId() {
            return `buyer_inquiry_create_${this.listing.id}`;
        },

        loggedIn() {
            return window.Spark.userId !== null;
        },

        buyerInquiryStart() {
            if (typeof window.Spark.old !== "undefined") {
                return window.Spark.old.buyer_inquiry_start;
            }
        },

        isOpen() {
            if (typeof this.buyerInquiryStart === "undefined") {
                return false;
            }

            let listingId = parseInt(this.listing.id, 10);

            return parseInt(this.buyerInquiryStart, 10) === listingId;
        }
    },

    methods: {
        openRequiresAuthModal() {
            window.Bus.$emit("requires-auth-modal:open");
        },

        handleModelOpen() {
            if (this.modalListingId !== this.listing.id) {
                this.body = "";
            }
        },

        handleModalClose() {
            this.modalListingId = this.listing.id;
        }
    },

    mounted() {
        window.Bus.$on(`${this.formId}.successfully-submitted`, response => {
            window.Bus.$emit("modal-should-close");

            // Clear out the message
            this.body = "";

            // Since nothing happens once complete let's do something
            // to make sure the user is aware something happened.
            this.contactingSeller = true;

            setTimeout(() => {
                this.currentUserActiveSpace = response.data.space;
                this.contactingSeller = false;
            }, 2000);
        });
    }
};
</script>
