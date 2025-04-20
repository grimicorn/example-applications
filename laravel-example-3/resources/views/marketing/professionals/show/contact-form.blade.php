@php
$isPreview = $isPreview ?? false;
@endphp
@if(isset($inModal) and $inModal)
<div class="professional-contact-form-wrap text-center" v-cloak>
@else
<div class="professional-contact-form-wrap">
@endif
    @if(Auth::check())
        @if(!isset($inModal) or !$inModal)
            <h2 class="mb-1 section-content-title">Email Me</h2>
        @endif

        <fe-form
        class="hide-labels marketing-professional-contact-form"
        form-id="marketing-professional-contact-form"
        submit-class="btn btn-color5 mb2-m width-100 bb1-m"
        :submit-right-aligned="true"
        :data-disabled="{{ $isPreview ? 'true' : 'false' }}"
        submit-label="Contact"
        action="{{ route('professional.contact.store', ['id' => $professional->id]) }}">
            {{-- Name --}}
            <input-textual
            name="name"
            value="{{ old('name', $isPreview ? null : $currentUser->name) }}"
            validation-message="{{ $errors->first('name') }}"
            label="Name"
            validation="required"
            :input-disabled="{{ $isPreview ? 'true' : 'false' }}"
            autofocus="autofocus"></input-textual>

            {{-- Phone --}}
            <input-textual
            type="phone"
            name="phone"
            value="{{ old('phone', $isPreview ? null : $currentUser->work_phone) }}"
            validation-message="{{ $errors->first('phone') }}"
            label="Phone"
            :input-disabled="{{ $isPreview ? 'true' : 'false' }}"
            placeholder="555-555-5555"></input-textual>

            {{-- Email --}}
            <input-textual
            type="email"
            name="email"
            value="{{ old('email', $isPreview ? null : $currentUser->email) }}"
            validation-message="{{ $errors->first('email') }}"
            label="Email"
            :input-disabled="{{ $isPreview ? 'true' : 'false' }}"
            placeholder="name@yourdomain.com"></input-textual>

            {{-- Message --}}
            <input-textual
            type="textarea"
            name="message"
            value="{{ old('message') }}"
            validation="required"
            validation-message="{{ $errors->first('message') }}"
            label="Message"
            placeholder="Type your message here."
            :input-disabled="{{ $isPreview ? 'true' : 'false' }}"
            input-rows="{{ isset($isModal) and $isModal ? '5' : '3' }}"></input-textual>

            <input
            type="hidden"
            name="professional_id"
            value="{{ $professional->id }}">
        </fe-form>
    @else
    <div class="professional-contact-login-message">
        @if(!isset($inModal) or !$inModal)
            <h2 class="mb-1">Email Me</h2>
            <p>Please login or register to contact me.</p>
            <a
            href="{{ route('professional.contact.show', ['id' => $professional->id]) }}"
            class="btn professional-contact-login-btn">Login/Register</a>
        @else
            <p>Please login or register to contact me.</p>
            <div class="text-center">
                <a href="/login" class="btn btn-color5 mr1">Login</a>
                <a href="/register" class="btn btn-color4">Join Now</a>
            </div>
        @endif
    </div>
    @endif
</div>
