<div class="contact-form-wrap">
    <fe-form
        form-id="marketing-contact-form"
        :remove-submit="true"
        class="marketing-contact-form"
    >
        {{-- Name --}}
        <input-textual
        name="name"
        value="{{ old('name', isset($currentUser) ? $currentUser->name : '') }}"
        validation-message="{{ $errors->first('name') }}"
        label="Name"
        validation="required"
        autofocus="autofocus"></input-textual>

        {{-- Phone --}}
        <input-textual
        type="phone"
        name="phone"
        value="{{ old('phone', isset($currentUser) ? $currentUser->work_phone : '') }}"
        validation-message="{{ $errors->first('phone') }}"
        label="Phone"
        placeholder="555-555-5555"
        validation="required"></input-textual>

        {{-- Email --}}
        <input-textual
        type="email"
        name="email"
        value="{{ old('email', isset($currentUser) ? $currentUser->email : '') }}"
        validation-message="{{ $errors->first('email') }}"
        label="Email"
        placeholder="name@yourdomain.com"
        validation="required"></input-textual>

        {{-- Preferred Method of Contact --}}
        <input-radio
        name="preferred_contact_method"
        value="{{ old('preferred_contact_method') }}"
        validation-message="{{ $errors->first('preferred_contact_method') }}"
        label="Preferred Method of Contact"
        :inputs="['Phone', 'Email']"
        validation="required"></input-radio>

        {{-- Message --}}
        <input-textual
        type="textarea"
        name="message"
        value="{{ old('message') }}"
        validation-message="{{ $errors->first('message') }}"
        label="Message"
        placeholder="Type your message here."
        :input-rows="5"
        validation="required"></input-textual>

        @include('shared.partials.recaptcha-check')


        <marketing-facebook-tracking-submit
            class="text-center"
            data-button-class="btn-color5"
            data-tracking-message="Lead"
        ></marketing-facebook-tracking-submit>
    </fe-form>
</div>
