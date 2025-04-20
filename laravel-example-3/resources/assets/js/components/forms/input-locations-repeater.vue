<template>
<div class="input-locations-repeater input-half is-left clear">
    <input-repeater
    :values="values"
    :default-value="defaultValue"
    :label="label"
    :add-new-label="addNewLabel">
        <template scope="props">
            <!-- State -->
            <input-select
            :name="`${name}[${props.index}][state]`"
            :value="props.input.state"
            label=""
            wrap-class=""
            :options="statesForSelect"
            placeholder="Select State"></input-select>
        </template>
    </input-repeater>
</div>
</template>

<script>
    module.exports = {
        data() {
            return {
                defaultValue: {
                     state: ''
                },
            }
        },

        computed: {
            statesForSelect() {
                let statesForSelect = window.Spark.statesForSelect;

                return (typeof statesForSelect === 'undefined') ? [] : statesForSelect;
            }
        },

        props: {
            label: {
                type: String,
                default: '',
            },

            name: {
                type: String,
                required: true,
            },

            values: {
                // type: Array,
                default() {
                    return [];
                },
            },

            addNewLabel: {
                type: String,
                default: 'Add Additional Location',
            },
        },
    };
</script>
