<modal
button-label="Report Abuse"
button-class="btn-link fc-color10 a-ul fz-14"
title="Report Abuse"
class="inline"
:display-cancel="true">
    <template scope="props" v-cloak>
        <fe-form
        form-id="report_abuse_form"
        submit-label="Submit Report"
        :submit-centered="true"
        submit-class="btn-color6"
        action="{{ $message->reportAbuseUrl() }}"
        method="POST"
        :auto-open="{{ old('message_id') === $message->id ? 'true' : 'false' }}"
        class="report-abuse-form app-fe-form">
            <input-select
            name="reason"
            label="Please select the reason why you reporting this message:"
            :options="['Inappropriate Language', 'Spam', 'Other']"
            validation="required"
            placeholder="Select Reason"
            value="{{ old('reason') }}"
            validation-message="{{ $errors->first('reason') }}"
            input-class="width-50"></input-select>

            <input-textual
            type="textarea"
            name="reason_details"
            label="Reason details"
            wrap-class="hide-labels"
            value="{{ old('reason_details') }}"
            validation-message="{{ $errors->first('reason_details') }}"
            placeholder="Please provide additional details."></input-textual>

            <input type="hidden" name="reporter_id" value="{{ $currentUser->id }}">
            <input type="hidden" name="message_id" value="{{ $message->id }}">
        </fe-form>
    </template>
</modal>
