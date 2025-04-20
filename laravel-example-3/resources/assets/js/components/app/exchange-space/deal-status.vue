<template>
    <div v-if="space && spaceDealTypes" class="exchange-space-wrap text-center">
        <div class="flex justify-center mb1">
            <div v-for="type in spaceDealTypes" :class="pillClasses(type)"></div>
        </div>
        <div class="fz-14 lh-solid text-uppercase" v-text="space.deal_label"></div>
    </div>
</template>

<script>
module.exports= {
    props: {
        space: {
            type: Object,
            required: true,
        }
    },

    data() {
        return {};
    },
    computed: {
        spaceDealTypes() {
            if (typeof window.Spark.spaceDealTypes==='undefined') {
                return [];
            }

            return window.Spark.spaceDealTypes;
        },
    },

    methods: {
        pillClasses(type) {
            type = parseInt(type, 10);

            return [
                type <=this.space.deal ? 'bg-color5': '',
                'progress-pill',
                'ba1-color5',
                (type===1) ? 'radius-left-10': '',
                (type===4) ? 'radius-right-10': '',
            ];
        },
    },
}

;
</script>
