<template>
    <a href=""><i
    class="fa fa-print fz-28 fc-color11 cursor hide-print"
    aria-hidden="true"
    @click="print"></i></a>
</template>

<script>
    module.exports = {
        name: 'print-page',

        props: {
            wrapSelector: {
                type: String,
                default: 'html',
            },
        },

        data() {
            return {};
        },

        computed: {},

        methods: {
            print() {
                window.print();
                // let printWindow = window.open();
                // printWindow.document.write(this.printContent);
                // printWindow.print();
                // printWindow.close();
            }
        },
    };
</script>
