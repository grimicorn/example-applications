@extends('layouts.app')

@section('content')
@php
$firstJob = \App\Job::find(1);
@endphp
<styleguide class="container m-auto py-8">
    <h1 class="mb-4">Styleguide</h1>

    <h2 class="mt-4 mb-2">Input File</h2>
    <input-file
        data-save-action="{{ route('styleguide') }}"
        data-name="input_file_example_1"
        data-label="Input File Example 1"
        :data-current-file="{
            file_name: 'demo.pdf',
            full_url:'http://placehold.it/800x800?text=DEMO'
        }"
    ></input-file>

    <input-file
        data-save-method="PATCH"
        data-label="Input File Example 2"
        data-save-action="{{ route('styleguide') }}"
        data-name="input_file_example_2"
    ></input-file>

    <h2 class="mb-2 mt-4">Buttons</h2>
    <h3>Regular Button</h3>
    <p class="mb-4"><em>The default button styling. Used by adding the <code>.button</code> class to the <code>&lt;button></code> attribute. Class is required to allow flexible use with the default <code>&lt;button></code> attribute.</em></p>

    <button class="button mb-4">Button!</button>

    <br>

    <h3>Ghost Button</h3>
    <p class="mb-4"><em>An outlined version of the button. Used by adding the <code>.button</code> and <code>.ghost</code> classes to the <code>&lt;button></code> attribute.</em></p>

    <button class="button ghost">Button!</button>

    <h2 class="mb-2 mt-4">Links</h2>
    <a href="#">This is a link!</a>

    <h2 class="mb-2 mt-4">Colors</h2>
    <p class="mb-4">Below are the colors used in the theme and their corresponding tailwinds assignment. You can add, edit, or remove colors using <code>tailwind.config.js</code>.</p>

    <div class="flex flex-wrap">
        <div class="overflow-hidden w-full sm:w-1/2 mb-4 sm:pr-4">
            <div class="text-white bg-red px-6 py-3 text-sm font-semibold flex justify-between">
                <span><code>red</code></span>
                <span class="font-normal opacity-75">#E53D2E</span>
            </div>
            <div class="text-white bg-red-dark px-6 py-3 text-sm font-semibold flex justify-between">
                <span><code>red-dark</code></span>
                <span class="font-normal opacity-75">#DE291B</span>
            </div>
            <div class="text-white bg-pink px-6 py-3 text-sm font-semibold flex justify-between">
                <span><code>pink</code></span>
                <span class="font-normal opacity-75">#EA6BD7</span>
            </div>
            <div class="text-white bg-blue px-6 py-3 text-sm font-semibold flex justify-between">
                <span><code>blue</code></span>
                <span class="font-normal opacity-75">#2A68B5</span>
            </div>
            <div class="text-white bg-green px-6 py-3 text-sm font-semibold flex justify-between">
                <span><code>green</code></span>
                <span class="font-normal opacity-75">#39AE4C</span>
            </div>
            <div class="text-white bg-yellow px-6 py-3 text-sm font-semibold flex justify-between">
                <span><code>yellow</code></span>
                <span class="font-normal opacity-75">#F5CF36</span>
            </div>
            <div class="text-white bg-orange px-6 py-3 text-sm font-semibold flex justify-between">
                <span><code>orange</code></span>
                <span class="font-normal opacity-75">#FF7239</span>
            </div>
            <div class="text-white bg-black px-6 py-3 text-sm font-semibold flex justify-between">
                <span><code>black</code></span>
                <span class="font-normal opacity-75">#000000</span>
            </div>
            <div class="text-white bg-gray-400 px-6 py-3 text-sm font-semibold flex justify-between">
                <span><code>gray-400</code></span>
                <span class="font-normal opacity-75">#001E27</span>
            </div>
            <div class="text-white bg-gray-300 px-6 py-3 text-sm font-semibold flex justify-between">
                <span><code>gray-300</code></span>
                <span class="font-normal opacity-75">#717479</span>
            </div>
            <div class="text-white bg-gray-200 px-6 py-3 text-sm font-semibold flex justify-between">
                <span><code>gray-200</code></span>
                <span class="font-normal opacity-75">#A2A6AC</span>
            </div>
            <div class="text-gray-400 bg-gray-100 px-6 py-3 text-sm font-semibold flex justify-between">
                <span><code>gray-100</code></span>
                <span class="font-normal opacity-75">#E8EBF1</span>
            </div>
            <div class="text-gray-400 bg-white px-6 py-3 text-sm font-semibold flex justify-between">
                <span><code>white</code></span>
                <span class="font-normal opacity-75">#FFFFFF</span>
            </div>
        </div>
    </div>

    <h2 class="mb-2 mt-4">Icon Legend</h2>
    <p>Icons can be created by using the Icon.vue component, and supplying an appropriate data-name from the verified list below. Unverified inputs will result in a console error while in Vue development mode. Some icons require a prefix other than `fas` which is the default you can specify this using the `data-prefix` property.</p>
    <pre>
        <code>
            &lt;icon data-name="data-name">&lt;/icon>
        </code>
    </pre>

    <table class="alternating-table">
        <tr>
            <th>Name</th>
            <th>Prefix</th>
            <th>Output</th>
        </tr>
        <tr>
            <td>tshirt</td>
            <td>fa</td>
            <td><icon data-name="tshirt"></icon></td>
        </tr>
        <tr>
            <td>arrow-right</td>
            <td>fa</td>
            <td><icon data-name="arrow-right"></icon></td>
        </tr>
    </table>

    <h2 class="mb-2 mt-4">Panel</h2>
    <panel>
        <template slot="toggle">
            <button class="button">
                Template Slot 'Toggle' Changes the Panel's Button Content
            </button>
        </template>
        <template slot="header">
            Header! Info!
        </template>
        <p>NOTE! This section doesn't have default padding so that you can add whatever full-width elements you need. (:</p>
        <p>This is a pop-up panel! It will pop up when you give the designated opening button a click. You can press `esc` to close this panel while focused on it, or you can click the arrow to close it.</p>

        <p>NOTE! This section doesn't have default padding so that you can add whatever full-width elements you need. (:</p>
        <p>This is a pop-up panel! It will pop up when you give the designated opening button a click. You can press `esc` to close this panel while focused on it, or you can click the arrow to close it.</p>
        <p>NOTE! This section doesn't have default padding so that you can add whatever full-width elements you need. (:</p>
        <p>This is a pop-up panel! It will pop up when you give the designated opening button a click. You can press `esc` to close this panel while focused on it, or you can click the arrow to close it.</p>
        <p>NOTE! This section doesn't have default padding so that you can add whatever full-width elements you need. (:</p>
        <p>This is a pop-up panel! It will pop up when you give the designated opening button a click. You can press `esc` to close this panel while focused on it, or you can click the arrow to close it.</p>
        <p>NOTE! This section doesn't have default padding so that you can add whatever full-width elements you need. (:</p>
        <p>This is a pop-up panel! It will pop up when you give the designated opening button a click. You can press `esc` to close this panel while focused on it, or you can click the arrow to close it.</p>
    </panel>

    <h2 class="mb-2 mt-4">Textarea Input</h2>

    <input-textarea
        data-name="input_textarea"
        data-label="Label!"
        value="Default Value"
    ></input-textarea>
    <h2 class="mb-2 mt-4">Input Checkbox</h2>

    <input-checkbox
        data-name="tshirt"
        data-label="Label"
        :value="true"
        data-error="This is an error!"
        data-instructions="These are instructions!"
    ></input-checkbox>
    <input-checkbox
        data-name="arrow-right"
        data-label="Different Label"
        :value="true"
    ></input-checkbox>

    <h2 class="mb-2 mt-4">Input Select</h2>
    <input-select
        data-label="Label"
        data-name="input_select_1"
        data-instructions="Instructions for using this search."
        data-error="Descriptive error text."
        data-placeholder="Select an option"
        :data-options="{
            option1: 'Option 1',
            option2: 'Option 2',
            option3: 'Option 3',
        }"
        >
    </input-select>

    <input-select
        data-label="Label"
        data-name="input_select_2"
        value="option1"
        :data-options="{
            option1: 'Option 1',
            option2: 'Option 2',
            option3: 'Option 3',
        }"
    >
    </input-select>

    <h2 class="mb-2 mt-4">Input Text</h2>
    <input-text
        data-name="email"
        data-type="email"
        :data-required="true"
        data-label="{{ __('Email') }}"
        data-instructions="Instructions for using this search."
        data-error="Descriptive error text."
        data-placeholder="Enter your email"
    ></input-text>

    <h2 class="mb-2 mt-4">Date Formating</h2>
    <p>
        Check out the <a href="https://date-fns.org/docs/format">docs</a> for formating.
    </p>
    <v-date
        data-output-format="MM/DD"
        data-date="{{ now() }}"
    ></v-date><br>

    <v-date
        data-output-format="MM/DD/YYYY"
        data-date="{{ now() }}"
    ></v-date><br>

    <v-date
        data-output-format="MM/DD/YYYY hh:mm:ss"
        data-date="{{ now() }}"
    ></v-date><br>

    <h2 class="mb-2 mt-4">Alerts</h2>
    <alert
        data-type="success"
    >
        Success alert
    </alert>

    <alert
        data-type="info"
    >
        Info alert
    </alert>

    <alert
        data-type="warning"
    >
        Warning alert
    </alert>

    <alert
        :data-dismissible="false"
        data-type="danger"
    >
        Danger alert
    </alert>

    <h2 class="mb-2 mt-4">Job Panel</h2>

    <job-panel :data-opened="false" :data-current-panel="1" :value="{{ optional($firstJob)->toJson() }}">
        <template slot="toggle">
            Job Panel Toggle
        </template>
    </job-panel>

    <h2 class="mb-2 mt-4">Job Card (Non-Specialty)</h2>

    <job-card
        :data-job="{{ optional($firstJob)->toJson() }}"
    ></job-card>
</styleguide>
@endsection
