@extends('layouts.application')

@section('content')
    @if($system_message)
        <fe-form
        method="POST"
        form-id="system_message_form"
        submit-label="Update"
        action="{{ route('system-message.update', ['id' => $system_message->id]) }}">
            <input-textual
                    label="Message"
                    name="message"
                    type="text"
                    placeholder="System Message"
                    value="{{ $system_message->message}}"></input-textual>
            <input-checkbox
                    label="Active"
                    name="active"
                    value="{{ $system_message->active }}"></input-checkbox>
            <input-select
                    name="urgency"
                    label="Urgency"
                    validation="required"
                    value="{{ $system_message->urgency }}"
                    :options="{{ json_encode($urgency_options) }}"
                    placeholder="Select Urgency"></input-select>
        </fe-form>
    @else
    <p>There is no system message available. Contact your developer.</p>
@endif
@endsection