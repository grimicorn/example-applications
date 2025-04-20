<div
class="mb3 flex
{{ ($isCreate) ? 'items-start' : '' }}">
    <div class="{{ ($isCreate) ? 'flex-1' : '' }}">
        <avatar
        class="mr2"
        src="{{ $currentUser->photo_thumbnail_small_url }}"
        width="44"
        height="44"
        image-class="app-navbar-avatar rounded"></avatar>
    </div>

    @if($isCreate)
    <div class="flex-11 flex items-start">
        <div class="flex-8 mr3">
            <input-textual
            name="title"
            placeholder="Title"
            label="Title"
            wrap-class="hide-labels"
            validation="required"
            value="{{ old('title') }}"
            input-class="fe-input-height"
            validation-message="{{ $errors->first('title') }}"></input-textual>
        </div>
        @isset($categoryOptions)
        <div class="flex-4">
            <input-select
            name="category"
            label="Category"
            :options="{{ json_encode($categoryOptions) }}"
            placeholder="Select Category"
            wrap-class="hide-labels"
            validation="required"
            value="{{ old('category') }}"
            validation-message="{{ $errors->first('category') }}"></input-select>
        </div>
        @endisset
    </div>
    @endif
</div>
