<form action="?" method="GET">
    <label>Model ID: <input type="text" name="model_id" value="{{ $model_id }}"></label><br>
    <button type="submit">Submit</button>
</form>

<ul>
    @foreach ($models as $model)
        <li>
            <a
            target="_blank"
            href="{{ route('model-to-json.show', array_filter([
                'model' => $model,
                'model_id' => $model_id,
            ])) }}">{{ $model }}</a>
        </li>
    @endforeach
</ul>
