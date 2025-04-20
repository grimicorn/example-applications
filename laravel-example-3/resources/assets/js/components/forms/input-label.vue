<template>
    <div class="input-label-wrap">
        <!-- Label -->
        <label
        :for="inputId"
        v-html="label"
        :class="labelClass"></label>

        <!-- Tooltip -->
        <span v-if="tooltip">&nbsp;</span>
        <tool-tip
        v-if="tooltip"
        direction="bottom">{{ tooltip }}</tool-tip>

        <!-- Maxlength -->
        <input-maxlength :value="inputValue" :maxlength="inputMaxlength"></input-maxlength>
    </div>
</template>

<script>
module.exports = {
    props: {
        inputId: {
            type: String,
            default: ""
        },
        label: {
            type: String,
            default: ""
        },
        labelClass: {
            type: Array,
            default() {
                return ["fe-input-label"];
            }
        },
        inputValue: {
            default: ""
        },
        inputMaxlength: {
            type: Number,
            default: -1
        },
        tooltip: {
            type: String,
            default: ""
        },
        hideColon: {
            type: Boolean,
            default: false
        }
    }
};
</script>
