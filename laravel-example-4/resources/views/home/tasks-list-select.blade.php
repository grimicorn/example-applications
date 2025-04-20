@unless(empty($list_options))
<div class="select-google-tasks-list">
    <h3>3. Select your Google Tasks list</h3>

    <form method="POST" action="{{ action('SelectListController@update') }}">
        {{ csrf_field() }}

        {{ method_field('PATCH') }}

        <select
        class="tasks-list-select"
        name="selected_list">
            @foreach($list_options as $option)
            <option
            @if($option['value'] === $user->selected_list)
            selected="selected"
            @endif
            value="{{ $option['value'] }}">
                {{ $option['label'] }}
            </option>
            @endforeach
        </select>

        <button
        class="btn btn-primary tasks-list-select"
        type="submit">Select List</button>
    </form>

    <ul>
        <li>
            This will update the request body example below.
        </li>
        <li>
            Lists can take up to 10 minutes to appear after created.
        </li>
    </ul>
</div>
@endunless
