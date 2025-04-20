<div class="profile-designations-wrap">

     {{-- Other Designation --}}
    <input-tags
    name="professionalInformation[other_designations]"
    :value="{{ json_encode(old(
        'professionalInformation.other_designations',
        $currentUser->professionalInformation->other_designations
    ), true) }}"
    validation-message="{{ $errors->first('professionalInformation.other_designations') }}"
    label="Professional Designations"></input-tags>
</div>
