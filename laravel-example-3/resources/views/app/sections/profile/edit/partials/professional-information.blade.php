<app-form-accordion
header-title="Professional Information"
class="profile-edit-professional-information">
    <div slot="content">
        {{-- Occupation --}}
        <input-select
        name="professionalInformation[occupation]"
        label="Occupation"
        value="{{ old('professionalInformation.occupation', $currentUser->professionalInformation->occupation) }}"
        :options="{{ json_encode($occupations) }}"
        placeholder="Occupation"
        wrap-class="input-half is-left"
        validation-message="{{ $errors->first('professionalInformation.occupation') }}"></input-select>

        {{-- Years of Experience --}}
        <input-textual
        validation="numeric"
        name="professionalInformation[years_of_experience]"
        value="{{ old('professionalInformation.years_of_experience', $currentUser->professionalInformation->years_of_experience) }}"
        validation-message="{{ $errors->first('professionalInformation.years_of_experience') }}"
        label="Years of Experience"
        wrap-class="input-half is-right"></input-textual>

        <div class="input-half is-left">
            {{-- Company Name --}}
            <input-textual
            name="professionalInformation[company_name]"
            value="{{ old('professionalInformation.company_name', $currentUser->professionalInformation->company_name) }}"
            validation-message="{{ $errors->first('professionalInformation.company_name') }}"
            label="Company Name"></input-textual>

            {{-- Links --}}
            <input-repeater
            class="professional-information-links"
            :values="{{ json_encode(old(
                'professionalInformation.links',
                $currentUser->professionalInformation->links
            )) }}"
            :default-value="['']"
            label="Links:"
            add-new-label="Add new link">
                <template scope="props">
                    <input-textual
                    type="url"
                    :name="`professionalInformation[links][${props.index}]`"
                    :value="typeof props.input === 'string' ? props.input : ''"
                    placeholder="Website Address"
                    wrap-class="hide-labels"></input-textual>
                </template>
            </input-repeater>
        </div>

        <div class="input-half is-right">
            {{-- Company Logo --}}
            <input-file-single-image
            name="company_logo_url"
            value="{{ old('company_logo_url_file') }}"
            validation-message="{{
                (null === $errors->first('company_logo_url_delete')) ? $errors->first('company_logo_url_delete') : $errors->first('company_logo_url_file')
            }}"
            label="Company Logo"
            :data-display-avatar="false"
            :data-max-size-mb="4"
            image-src="{{ $currentUser->professionalInformation->company_logo_upload_url }}"
            image-width="155"
            image-height="175"
            :display-photo-default-user-initials="false"></input-file-single-image>
        </div>

        {{-- Professional Background --}}
        <input-textual
        type="textarea"
        name="professionalInformation[professional_background]"
        value="{{ old('professionalInformation.professional_background', $currentUser->professionalInformation->professional_background) }}"
        validation-message="{{ $errors->first('professionalInformation.professional_background') }}"
        label="Services Offered"
        placeholder="Description of services offered."
        wrap-class="clear"></input-textual>

        {{-- Business Broker Designations --}}
        @include('app.sections.profile.edit.partials.professional-information.designations')

    </div>
</app-form-accordion>
