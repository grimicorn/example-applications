<app-form-accordion
header-title="Contact Information"
class="profile-edit-contact-information">
    <div slot="content">
        {{-- First Name --}}
        <input-textual
        name="first_name"
        value="{{ old('first_name', $currentUser->first_name) }}"
        validation-message="{{ $errors->first('first_name') }}"
        label="First Name"
        wrap-class="input-half is-left"
        validation="required"
        :input-readonly="true"></input-textual>

        {{-- Last Name --}}
        <input-textual
        name="last_name"
        value="{{ old('last_name', $currentUser->last_name) }}"
        validation-message="{{ $errors->first('last_name') }}"
        label="Last Name"
        wrap-class="input-half is-right"
        validation="required"
        :input-readonly="true"></input-textual>

        {{-- Email --}}
        <input-textual
        type="email"
        name="email"
        value="{{ old('email', $currentUser->email) }}"
        validation-message="{{ $errors->first('email') }}"
        label="Email"
        wrap-class="input-half is-left"
        validation="required"
        :input-readonly="true"></input-textual>

        {{-- Work Phone --}}
        <input-textual
        type="phone"
        name="work_phone"
        value="{{ old('work_phone', $currentUser->work_phone) }}"
        validation-message="{{ $errors->first('work_phone') }}"
        label="Work Phone"
        wrap-class="input-half is-right"></input-textual>
    </div>
</app-form-accordion>
