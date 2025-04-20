@php
$isCreate = isset($isCreate) ? $isCreate : false;
@endphp

@if($conversation->isReadonly())
    <div class="pa2">
        <div class="pt2 pb1 pl2 pr1 flex items-center">
            <div class="pr3 mr3 fz-18">
                This Business Inquiry has been accepted and an Exchange Space has been created where you can invite advisors and begin the due diligence process.
            </div>

            <a
              href="{{ route('exchange-spaces.show', ['id' => $space->id]) }}"
              class="btn btn-color4"
            >Click here to get started</a>
        </div>
    </div>
@else
    <div
    class="exchange-space-message-input hide-print mb3">
        @include('shared.form-flashed-alerts')

        <fe-form
        form-id="exchange_space_message_form"
        :remove-submit="true"
        enctype="multipart/form-data"
        action="{{ $storeLink }}"
        :enable-redirect="true"
        :disabled-unload="true"
        method="{{ ($isSpace || $isCreate) ? 'POST' : 'PATCH' }}"
        class="exchange-space-message-form pt3 pb3 pl4 pr3 flex
        {{ ($isSpace) ? 'bg-color9' : 'bg-color8' }}
        {{ ($isCreate) ? 'flex-column' : '' }}">
            @include('app.sections.shared.exchange-space.message-input-header', [
                'isSpace' => $isSpace,
                'isCreate' => $isCreate,
            ])

            <div class=" bg-color2 {{ ($isCreate) ? '' : 'flex-11 ml1' }}">
                <input-textual
                name="body"
                type="textarea"
                placeholder="Enter message here (required)"
                input-class="ba0"
                wrap-class="mb0 bb2 hide-labels"
                value="{{ old('body') }}"
                validation="required"
                validation-message="{{ $errors->first('body') }}"></input-textual>

                @include('app.sections.shared.exchange-space.message-input-upload')

                @include('app.sections.shared.exchange-space.message-input-button-bar')
            </div>
        </fe-form>
    </div>
@endif
