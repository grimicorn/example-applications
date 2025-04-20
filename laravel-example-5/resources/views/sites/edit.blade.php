@extends('layouts.app')

@section('content')
@include('partials.app-title', [
    'title' => 'Edit Site',
])

    <ajax-form
        class="md:w-1/2"
        data-id="site_edit"
        data-method="PATCH"
        data-action="{{ route('sites.update', ['site' => $site]) }}"
        data-submit-label="Edit"
    >
        <template slot="inputs">
            {{-- Name --}}
            <input-text
                data-type="text"
                data-label="Name"
                data-name="name"
                data-value="{{ $site->name }}"
                data-placeholder="srcWatch"
                :data-required="true"
            ></input-text>

            {{-- Sitemap URL --}}
            <input-text
                data-type="url"
                data-label="Sitemap URL"
                data-name="sitemap_url"
                data-value="{{ $site->sitemap_url }}"
                data-placeholder="{{ url('sitemap.xml') }}"
                :data-required="true"
            ></input-text>

            {{-- Difference Threshold --}}
            <input-text
                data-type="number"
                data-label="Difference Threshold"
                data-name="difference_threshold"
                data-value="{{ $site->difference_threshold }}"
                data-placeholder="0.95"
                data-min="0"
                data-max="1"
                data-step=".05"
            ></input-text>
        </template>
    </ajax-form>
@endsection
