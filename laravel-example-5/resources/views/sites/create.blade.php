@extends('layouts.app')

@section('content')
    @include('partials.app-title', [
        'title' => 'Add New',
    ])

    <ajax-form
        class="md:w-1/2"
        data-id="site_create"
        data-method="POST"
        data-action="{{ route('sites.store') }}"
        data-submit-label="Create"
    >
        <template slot="inputs">
            {{-- Name --}}
            <input-text
                data-type="text"
                data-label="Name"
                data-name="name"
                data-placeholder="srcWatch"
                :data-required="true"
            ></input-text>

            {{-- Sitemap URL --}}
            <input-text
                data-type="url"
                data-label="Sitemap URL"
                data-name="sitemap_url"
                data-placeholder="{{ url('sitemap.xml') }}"
                :data-required="true"
            ></input-text>

            {{-- Difference Threshold --}}
            <input-text
                data-type="number"
                data-label="Difference Threshold"
                data-name="difference_threshold"
                data-value="0.95"
                data-min="0"
                data-max="1"
                data-step=".05"
            ></input-text>
        </template>
    </form>
@endsection
