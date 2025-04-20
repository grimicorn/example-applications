<template>
    <fe-form
    form-id="application-styleguide"
    action="/dashboard/styleguide/inputs"
    method="PATCH"
    enctype="multipart/form-data"
    class="application-styleguide app-fe-form"
    :should-ajax="submitViaAjax">

    <button type="submit">Submit</button>

    <h2>Submission Configuration</h2>
    <ul>
        <li>
            <label>
                <input
                type="checkbox"
                name="force_validation_errors"
                value="on"
                :checked="dataForceValidationErrors">
                Force Validation Errors
            </label>
        </li>
        <li>
            <label>
                <input
                type="checkbox"
                name="submit_via_ajax"
                value="on"
                v-model="submitViaAjax">
                Submit via AJAX ({{ submitViaAjax }})
            </label>
        </li>
        <li>
            <label>
                <input
                type="checkbox"
                name="disabled_unload"
                value="on"
                v-model="disabledUnload">
                Disabled Unload Alert ({{ disabledUnload }})
            </label>
        </li>
    </ul>

    <h2>Available Inputs</h2>

    <p>
        Form inputs share a base mixin for properties and shared functionality found at <code>/resources/assets/js/mixins/input-mixin.js</code>. All inputs are required to have a name property other properties can be seen in the <code>props</code> object in <code>input-mixin.js</code>.
    </p>

    <h3>Textual Inputs</h3>

    <ul class="list-unstyled">
        <li>
            <input-rating
            name="rating_example"></input-rating>
        </li>

        <li>
            <input-textual
            type="url"
            name="input_url"
            value=""
            placeholder="Input URL placeholder"
            label="Input URL (input-textual.vue)"></input-textual>
        </li>

        <li>
            <input-textual
            type="textarea"
            name="input_textarea"
            value=""
            label="Input Textarea (input-textual) - With max length (input-maxlength.vue)"
            placeholder="Summary background to be included in search results table. Max 400 characters."
            :input-maxlength="2"></input-textual>
        </li>

        <li>
            <input-textual
            name="input_text"
            value=""
            placeholder="Input text placeholder"
            label="Input text (input-textual.vue)"></input-textual>
        </li>

        <li>
            <p>
                To have 50/50 inputs pass 'input-half' to the wrap-class attribute.
            </p>
            <div class="clear"></div>
            <input-textual
            name="input_text_left"
            value=""
            wrap-class="input-half is-left"
            placeholder="Input text left placeholder"
            label="Input text (Half left)"></input-textual>
            <input-textual
            name="input_text_right"
            value=""
            wrap-class="input-half is-right"
            placeholder="Input text right placeholder"
            label="Input text (Half right)"></input-textual>
        </li>
        <li class="clear">
            <input-textual
            type="email"
            name="input_email"
            value=""
            placeholder="Input email placeholder"
            label="Input email (input-textual.vue"></input-textual>
        </li>

        <li>
            <p>Hidden input (input-hidden.vue)</p>
            <input-hidden name="input_hidden"></input-hidden>
        </li>

        <li>
            <input-textual
            type="number"
            name="input_number"
            value=""
            placeholder="Input number placeholder"
            label="Input number (input-textual.vue"
            :input-step="1"
            :input-max="10"
            :input-min="1"></input-textual>
        </li>

        <li>
            <input-textual
            type="password"
            name="input_password"
            value=""
            placeholder="Input password placeholder"
            label="Input password (input-textual.vue"></input-textual>
        </li>

        <li>
            <input-textual
            type="phone"
            name="input_phone"
            value=""
            placeholder="Input phone placeholder"
            label="Input phone (input-textual.vue"></input-textual>
        </li>

        <li>
            <input-textual
            type="price"
            name="input_price"
            value=""
            placeholder="Input price placeholder"
            label="Input price - Non-Negative (input-textual.vue)"></input-textual>
        </li>

        <li>
            <input-textual
            type="price"
            name="input_price_negative_positive"
            :data-enable-negative="true"
            value=""
            placeholder="Input price placeholder"
            label="Input price - Negative and Positive (input-textual.vue)"></input-textual>
        </li>
    </ul>

    <h2>Todo</h2>
    <ul class="list-unstyled">
        <li>form.vue</li>
        <li>input-repeater.vue</li>
        <li>input-error-message.vue</li>
        <li>
            <input-label
            input-id="input_id"
            label="Label (input-label.vue)"></input-label>
        </li>

        <li>
            <input-range
            label="Input Range"
            name="range"
            :value="25">
                <template
                slot="status-message"
                scope="props">
                    Distance: {{ props.value }} Miles
                </template>
            </input-range>
        </li>

        <li>
            <input-toggle
            name="input_toggle"
            value=""
            label="Input Toggle (input-toggle.vue)"></input-toggle>
        </li>

        <li>
            <input-checkbox
            name="input_checkbox"
            value=""
            label="Input Checkbox (input-checkbox.vue)"
            ></input-checkbox>
        </li>

        <li>
            <input-boolean
            name="input_checkbox_boolean"
            value=""
            label="Checkbox Boolean (input-boolean.vue)"></input-boolean>
        </li>

        <li>
            <input-datepicker
            name="input_datepicker"
            value=""
            label="Datepicker (input-datepicker.vue)"
            placeholder="Datepicker (input-datepicker.vue)"></input-datepicker>
        </li>

        <li>input-file-single-image.vue</li>

        <li>input-locations-repeater.vue</li>
        <li>input-maxlength.vue</li>

        <li>
            <input-multi-checkbox
            name="input_multi_checkbox"
            :inputs="['Value 1', 'Value 2']"
            label="Input Multi Checkbox (input-multi-checkbox.vue)"
            :values="[]"
            ></input-multi-checkbox>
        </li>

        <li>
            <input-radio
            name="input_radio"
            value=""
            label="Input Radio (input-radio.vue)"
            :inputs="['Option 1', 'Option 2', 'Option 3']"></input-radio>
        </li>

        <li>
                <input-min-max-price
                label="Input {min} to {max} price"
                name="input_min_to_max_price"
                min-value=""
                max-value=""></input-min-max-price>
        </li>

        <li>
                <input-min-max-price
                label="Input {min} to {max} price (Fixed values)"
                name="input_min_to_max_price_fixed_values"
                :min-select-values="[
                    100000,250000,500000,1000000,
                ]"
                :max-select-values="[
                    100000,250000,500000,1000000,
                ]"></input-min-max-price>
        </li>

        <li>
            <input-business-category-select
            name="input_business_category_select"
            label="Business Category Select"
            :value="[]"></input-business-category-select>
        </li>

        <li>
            <input-select
            name="input_select"
            label="Input Select"
            value=""
            :options="['Option1', 'Option2', 'Option3']"
            placeholder="Input Select Placeholder"></input-select>
        </li>

        <li>
            <input-select
            :multiple="true"
            name="input_select_multiple"
            label="Input Select - Multiple"
            :value="[]"
            :options="['Option1', 'Option2', 'Option3']"
            placeholder="Input Select Multiple Placeholder"></input-select>
        </li>

        <li>input-tags.vue</li>
    </ul>
    </fe-form>
</template>

<script>
export default {
    props: {
        dataForceValidationErrors: {
            type: Boolean,
            default: false
        },

        dataSubmitViaAjax: {
            type: Boolean,
            default: false
        },

        dataDisabledUnload: {
            type: Boolean,
            default: true
        }
    },

    data() {
        return {
            submitViaAjax: this.dataSubmitViaAjax,
            disabledUnload: this.dataDisabledUnload
        };
    },

    computed: {},

    methods: {}
};
</script>
