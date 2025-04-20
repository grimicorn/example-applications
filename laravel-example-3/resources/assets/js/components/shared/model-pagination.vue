<template>
    <div
    class="pagination-wrap"
    :class="{
        'is-navigating': isNavigating,
        'text-right': !this.alignLeft,
        'text-left': this.alignLeft,
    }"
    v-if="this.lastPage > 1">
        <div
        v-show="isNavigating"
         class="pagination-navigating-cover">
            <i class="fa fa-refresh fa-spin" aria-hidden="true"></i>
        </div>

        <ul class="pagination text-right">
            <li
            class="pagination-previous pagination-page"
            @click="setPage(previousPage)"
            v-if="previousPage">
                <a
                :href="paginated.prev_page_url"
                @click="handleNavigation($event)">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                </a>
            </li>

            <li
            class="pagination-page"
            :class="{
                'is-active': isActive(page),
            }"
            v-for="page in pages"
            @click="setPage(page)">
                <span
                v-if="isActive(page)"
                v-text="page"></span>

                <a
                v-else
                :href="pageUrl(page)"
                v-text="page"
                @click="handleNavigation($event)"></a>
            </li>

            <li
            class="pagination-next pagination-page"
            @click="setPage(nextPage)"
            v-if="nextPage">
                <a
                :href="paginated.next_page_url"
                @click="handleNavigation($event)">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
module.exports = {
    name: "app-model-pagination",

    props: {
        paginated: {
            type: Object,
            required: true
        },

        range: {
            type: Number,
            default: 4
        },

        isNavigating: {
            type: Boolean,
            default: false
        },

        allowNavigation: {
            type: Boolean,
            default: false
        },

        alignLeft: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        pageRange() {
            // The page range should be between the previous and next pages.
            // Along with having enough pages to satisfy the range.
            let availablePages = this.lastPage - 2;

            return this.range < availablePages ? this.range : availablePages;
        },

        rangeOffset() {
            // We need an even range before and after as long as the amount of pages allows for that.
            // So removing the current page you need to half the range.
            let range = (this.pageRange - 1) / 2;
            return range < 1 ? 1 : range;
        },

        beforeMin() {
            let afterRange = this.afterMax - this.currentPage;
            afterRange = afterRange >= 0 ? afterRange : 0;
            let diffRange = this.pageRange - afterRange - 1;
            let beforeMin = diffRange > 0 ? this.currentPage - diffRange : 1;

            return beforeMin > 1 ? beforeMin : 1;
        },

        afterMax() {
            let rangeOffset = Math.ceil(this.rangeOffset);

            // So we are in the first few pages so we will need to account for that offset.
            if (this.currentPage < rangeOffset) {
                return this.currentPage + (this.pageRange - this.currentPage);
            }

            // Now that we are further into the pages then we can just figure out the offset
            // based off of the range minus the current page divided in half.
            let afterRange = rangeOffset;
            let afterMax = this.currentPage + afterRange;

            return afterMax > this.lastPage ? this.lastPage : afterMax;
        },

        pages() {
            let pages = [];

            // Add the before pages
            for (var i = this.beforeMin; i < this.currentPage; i++) {
                pages.push(i);
            }

            // Add the current page
            pages.push(this.currentPage);

            // Add pages after.
            for (var i = this.currentPage + 1; i <= this.afterMax; i++) {
                pages.push(i);
            }

            return pages;
        },

        currentPage() {
            return parseInt(this.paginated.current_page, 10);
        },

        lastPage() {
            return parseInt(this.paginated.last_page, 10);
        },

        previousPage() {
            let previousPage = this.currentPage - 1;

            return previousPage > 1 ? previousPage : 0;
        },

        nextPage() {
            let nextPage = this.currentPage + 1;

            return nextPage > this.lastPage ? 0 : nextPage;
        }
    },

    methods: {
        setPage(page) {
            // If the page is doing something such as filtering this will disable the ability to set a new page.
            // This way we are not hammering the server with requests.
            if (this.isNavigating) {
                return;
            }

            this.paginated.current_page = page;
            this.$emit("change", parseInt(page, 10));
        },

        handleNavigation(event) {
            if (!this.allowNavigation) {
                event.preventDefault();
            }
        },

        pageUrl(page) {
            let path = this.paginated.path;
            let seperator = path.indexOf("?") === -1 ? "?" : "&";

            return `${path}${seperator}page=${page}`;
        },

        isActive(page) {
            return page === this.currentPage;
        }
    }
};
</script>
