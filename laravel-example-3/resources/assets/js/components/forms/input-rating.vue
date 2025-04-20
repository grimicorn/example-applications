<template>
    <div class="input-rating">
        <!-- Label -->
        <input-label
        :input-id="inputId"
        :label="inputLabel"
        :hideColon="hideColon"
        :label-class="labelClass"
        :input-value="inputValue"
        :input-maxlength="inputMaxlength"
        :tooltip="tooltip"></input-label>

        <!-- Rating -->
        <div class="input-rating-stars">
            <i
            v-for="n in 5"
            :key="n"
            class="fa input-rating-star"
            :class="{
                'fa-star-o':  n > rating,
                'fa-star': n <= rating
            }"
            aria-hidden="true"
            @click="setRating(n)"></i>
        </div>

        <input
        :name="`${name}_rating`"
        :id="`${name}_rating`"
        type="hidden"
        :value="rating">

        <!-- Feedback -->
        <input-textual
        wrap-class="hide-labels"
        type="textarea"
        :placeholder="inputPlaceholder"
        :name="`${name}_feedback`"
        :id="`${name}_feedback`"></input-textual>
    </div>
</template>

<script>
export default {
    mixins: [require("./../../mixins/input-mixin")],

    props: {
        // name: {
        //     type: String,
        //     required: true,
        // },
        // id: {
        //     type: String,
        //     default: '',
        // },
        label: {
            type: String,
            default:
                "How would you rate your overall experience using Firm Exchange?"
        },
        placeholder: {
            type: String,
            default:
                "Use this field to share any specific feedback or experiences (optional)"
        },

        hideColon: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            rating: 0
        };
    },

    computed: {},

    methods: {
        setRating(index) {
            this.rating = this.rating === 1 && index === 1 ? 0 : index;
        }
    }
};
</script>
