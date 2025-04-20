<app-form-accordion
header-title="Personal Information"
class="profile-edit-personal-information">
    <div slot="content">
        {{-- Roles --}}
        <input-multi-checkbox
                name="primary_roles"
                :inputs="{{ json_encode($primaryRolesForSelect) }}"
                label="Primary User Role(s)"
                :values="{{ json_encode(old(
            'primary_roles',
            $currentUser->primary_roles
        ))}}"
        wrap-class="input-half is-left"
        ></input-multi-checkbox>

        {{-- User Photo --}}
        <input-file-single-image
                name="photo_url"
                value="{{ old('photo_url_file') }}"
                validation-message="{{
            (null === $errors->first('photo_url_delete')) ? $errors->first('photo_url_delete') : $errors->first('photo_url_file')
        }}"
        :data-max-size-mb="4"
        label="User Photo"
        wrap-class="input-half is-right avatar-upload"
        image-src="{{ $currentUser->photo_upload_url }}"
        image-width="180"
        image-height="180"></input-file-single-image>

        {{-- First Name --}}
        <input-textual
                name="first_name"
                value="{{ old('first_name', $currentUser->first_name) }}"
                validation-message="{{ $errors->first('first_name') }}"
                label="First Name"
                wrap-class="input-half is-left"
                validation="required"
                :input-readonly="false"></input-textual>

        {{-- Last Name --}}
        <input-textual
                name="last_name"
                value="{{ old('last_name', $currentUser->last_name) }}"
                validation-message="{{ $errors->first('last_name') }}"
                label="Last Name"
                wrap-class="input-half is-right"
                validation="required"
                :input-readonly="false"></input-textual>

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

        {{-- Bio --}}
        <input-textual
                type="textarea"
                name="bio"
                value="{{ old('bio', $currentUser->bio) }}"
                validation-message="{{ $errors->first('bio') }}"
                label="Bio"
                :input-maxlength="2500"
                wrap-class="clear"
                placeholder="Background"></input-textual>
    </div>
</app-form-accordion>
