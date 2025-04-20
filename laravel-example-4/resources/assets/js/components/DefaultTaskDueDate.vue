<template>
    <form
    :action="action"
    method="POST"
    class="default-task-due-date">
        <input type="hidden" name="_token" :value="crsfToken">
        <input type="hidden" name="_method" value="PATCH">

        <label class="default-task-due-date-label">
            <i
            class="fa"
            :class="{ 'fa-check-square-o': isEnabled, 'fa-square-o': !isEnabled}"
            aria-hidden="true"></i>

            <input
            type="checkbox"
            class="sr-only"
            name="due_date_enabled"
            v-model="isEnabled"
            value="1">
            Enable
        </label>

        <div>
            <input
            type="number"
            v-model="offsetCountValue"
            v-if="'0' !== offsetSelected && isEnabled"
            step="1"
            min="1"
            class="default-task-due-date-input"
            name="due_date_offset_days"
            :size="2">

            <select
            class="default-task-due-date-select"
            v-model="offsetSelected"
            v-if="isEnabled">
                <option value="0">Current Day</option>
                <option value="1" v-text="inFutureLabel"></option>
            </select>

            <input
            type="number"
            class="default-task-due-date-input"
            step="1"
            min="1"
            max="12"
            v-model="hourValue"
            @change="prefixHourZero"
            name="due_date_hour"
            size="2"
            v-if="isEnabled">

            <span v-if="isEnabled">:</span>

            <input
            type="number"
            class="default-task-due-date-input"
            step="1"
            min="0"
            max="59"
            v-model="minuteValue"
            @change="prefixMinuteZero"
            name="due_date_minute"
            size="2"
            v-if="isEnabled">

            <select
            class="default-task-due-date-select"
            name="due_date_period"
            v-model="period"
            v-if="isEnabled">
                <option value="am">AM</option>
                <option value="pm">PM</option>
            </select>

            <button
            class="btn-primary btn"
            type="submit">
                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                Save
            </button>
        </div>
    </form>
</template>

<script>
    export default {
        data () {
            return {
                offsetCountValue: this.offset,
                offsetSelected: 0 === this.offset ? '0' : '1',
                isEnabled: this.enabled,
                hourValue: this.hour,
                minuteValue: this.minute,
            };
        },

        computed: {
            inFutureLabel() {
                return (this.offsetCountValue > 1) ? 'Days in the future' : 'Day in the future';
            },
        },

        watch: {
            offsetSelected(newOffsetSelected) {
                if ('0' === newOffsetSelected) {
                    this.offsetCountValue = 0;
                } else {
                    this.offsetCountValue = 1;
                }
            },
        },

        methods: {
            prefixMinuteZero() {
                let minuteValue = parseInt( this.minuteValue, 10 );
                this.minuteValue = ( minuteValue <= 9 ) ? '0' + minuteValue : minuteValue;
            },
            prefixHourZero() {
                let hourValue = parseInt( this.hourValue, 10 );
                this.hourValue = ( hourValue <= 9 ) ? '0' + hourValue : hourValue;
            },
        },

        props: {
            enabled: {
                type: Boolean,
                default: false,
            },

            offset: {
                type: Number,
                default: 0,
            },

            hour: {
                type: Number,
                default: 9,
            },

            minute: {
                type: Number,
                default: 0,
            },

            period: {
                type: String,
                default: 'pm',
            },

            action: {
                type: String,
                required: true,
            },

            crsfToken: {
                type: String,
                required: true,
            },
        },

        mounted() {
            this.prefixMinuteZero();
            this.prefixHourZero();
        },
    };
</script>
