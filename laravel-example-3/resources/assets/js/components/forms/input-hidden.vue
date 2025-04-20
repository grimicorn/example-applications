<template>
    <div
    class="fe-input-type-hidden-wrap"
    :class="inputWrapClass">
        <input
        type="hidden"
        :name="name"
        :id="inputId"
        class="fe-input-type-hidden"
        :class="inputClasses"
        v-model="inputValue"
        v-validate="validationRules">

        <input-error-message
        :message="errorMessage"></input-error-message>
    </div>
</template>

<script>
let inputMixins = require("./../../mixins/input-mixin.js");

module.exports = {
    mixins: [inputMixins]
};
</script>
