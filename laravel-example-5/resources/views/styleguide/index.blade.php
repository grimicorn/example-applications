@extends('layouts.app')

@section('content')
    @include('partials.app-title', [
        'title' => 'Styleguide',
    ])

    <h2>Buttons</h2>

    <a href="#" class="button">.button</a><br><br>

    <a href="#" class="button-ghost">.button-ghost</a><br><br>

    <a href="#" class="button-link">.button-link</a><br><br>

    <button disabled="disabled">button[disabled]</button><br><br>

    <a href="#" class="button disabled">.button.disabled</a><br><br>

    <a href="#" class="button-link disabled">.button-link.disabled</a><br><br>

    <a href="#" class="button-ghost disabled">.button.disabled</a><br><br>


    <h2>Alerts</h2>

    <alert data-type="info">
        Something happened...
    </alert>

    <alert data-type="success">
        Something good happened!
    </alert>

    <alert data-type="warning">
        Something possibly bad happened...
    </alert>

    <alert data-type="danger" :data-dismissible="false">
        Something really bad happened...
    </alert>

    <h2>Inputs</h2>

    <input-text
        class="w-1/2"
        data-type="text"
        data-label="Text"
        data-name="input_text"
        data-id="input_text"
        data-value="Some text"
        data-placeholder="Text..."
        data-error="Error message"
        data-instructions="Instructional message"
        data-input-class="my-class"
        :data-readonly="true"
        :data-required="true"
    ></input-text>

    <input-text
        class="w-1/2"
        data-type="search"
        data-label="Search"
        data-name="input_search"
        data-id="input_search"
        data-value="Some keyword"
        data-placeholder="Keyword"
        data-instructions="Instructional message"
    ></input-text>

    <input-text
        class="w-1/2"
        data-type="url"
        data-label="URL"
        data-name="input_url"
        data-id="input_url"
        data-value="Some url"
        data-placeholder="http://example.com"
        data-instructions="Instructional message"
    ></input-text>

    <input-text
        class="w-1/2"
        data-type="number"
        data-label="Number"
        data-name="input_number"
        data-id="input_number"
        :data-value="20"
        data-placeholder="whole numbers 0-100"
        data-instructions="Instructional message"
        data-min="0"
        data-max="100"
        data-step="1"
    ></input-text>

    <input-checkbox
        class="w-1/2"
        data-label="Checkbox"
        data-name="input_checkbox"
        data-id="input_checkbox"
        :data-value="true"
        data-error="Error message"
        data-instructions="Instructional message"
        data-input-class="my-class"
    ></input-checkbox>

    <input-file
        class="w-1/2"
        data-label="File"
        data-name="input_file"
        data-id="input_file"
        data-error="Error message"
        data-instructions="Instructional message"
        data-input-class="my-class"
        :data-multiple="true"
        :data-display-upload-label="false"
        :data-accept="['.jpg', '.png']"
    ></input-file>

    <input-images
        class="w-1/2"
        data-label="Images"
        data-name="input_images"
        data-id="input_images"
        data-error="Error message"
        data-instructions="Instructional message"
        data-input-class="my-class"
        :data-images="[
            {
                name: 'test.png',
                src: 'http://placehold.it/355x355',
            },

            {
                name: 'test.png',
                src: 'http://placehold.it/255x255',
            },

            {
                name: 'test.png',
                src: 'http://placehold.it/155x155',
            }
        ]"
    ></input-images>

    <input-image
        class="w-1/2"
        data-label="Image"
        data-name="input_image"
        data-id="input_image"
        data-error="Error message"
        data-instructions="Instructional message"
        data-input-class="my-class"
        :data-value="{
            name: 'test.png',
            src: 'http://placehold.it/355x355',
        }"
    ></input-image>

    <input-toggle
        class="w-1/2"
        data-label="Toggle"
        data-name="input_toggle"
        data-id="input_toggle"
        :data-value="true"
        data-instructions="Instructional message"
        data-error="Error message"
    />

@endsection
